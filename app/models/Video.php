<?php declare(strict_types=1);



class Video
{
    private $file;
    private $probed;

    private $width;
    private $height;
    private $nbframes;



    public function __construct(string $file)
    {
        $this->file = $file;
        $this->probed = false;
    }



    public function probe() : void
    {
        if ($this->probed) { return; }

        $cmd = "ffprobe -v quiet -print_format json -show_error";
        $cmd .= " -show_format -show_streams -select_streams v:0 ";
        $cmd .= escapeshellarg($this->file);

        $result = $this->pexec($cmd, $code, $stdout, $stderr);
        if ($result === false) { throw new \Exception("failed to execute ffprobe"); }

        $json = json_decode($stdout, true);

        if (isset($json['error']))
        {
            $msg = $json['error']['string'] ?? "ffprobe returned unknown error";
            throw new \Exception($msg);
        }

        if (!(isset($json['streams']) && isset($json['streams'][0])))
        {
            throw new \Exception("no video streams in file");
        }

        $stream = $json['streams'][0];
        $this->width = intval($stream['width']) ?? 0;
        $this->height = intval($stream['height']) ?? 0;
        $this->nbframes = intval($stream['nb_frames']) ?? 0;

        if ($this->width <= 0 || $this->height <= 0 || $this->nbframes <= 0)
        {
            throw new \Exception("missing video frame information");
        }

        $this->probed = true;
    }



    public function getFrameWidth() : int
    {
        $this->probe();
        return $this->width;
    }


    public function getFrameHeight() : int
    {
        $this->probe();
        return $this->height;
    }


    public function createPreview(string $path, int $width = null, int $height = null) : void
    {
        $this->probe();

        if ($width === null) { $width = $this->width; }
        if ($height === null) { $height = $this->height; }

        $frame = min(10, $this->nbframes - 1);
        $scaled = !($this->width === $width && $this->height === $height);

        $cmd = "ffmpeg -y -i " . escapeshellarg($this->file) . " -vf \"";
        $cmd .= "select=eq(n\\,{$frame}),";

        if ($scaled)
        {
            $cmd .= "scale=w={$width}:h={$height}";
            $cmd .= ":flags=accurate_rnd+full_chroma_int+full_chroma_inp";
        }

        $cmd .= "\" -vframes 1 " . escapeshellarg($path);

        $result = $this->pexec($cmd, $code, $stdout, $stderr);

        if ($result === false || $code !== 0)
        {
            throw new \Exception("failed to extract video frame");
        }
    }


    private function pexec(string $cmd, &$code = null, &$stdout = null, &$stderr = null) : bool
    {
        $pconf =
        [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $pipes = [];
        $stdout = '';
        $stderr = '';

        $proc = proc_open($cmd, $pconf, $pipes);
        if (!is_resource($proc)) { return false; }

        $status = proc_get_status($proc);

        for ($i = 500; $status['running'] && $i > 0; --$i)
        {
            usleep(10000);
            $status = proc_get_status($proc);
            $stdout .= stream_get_contents($pipes[1]);
            $stderr .= stream_get_contents($pipes[2]);
        }

        if ($status['running']) { proc_terminate($proc); return false; }

        $code = $status['exitcode'];
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);
        return true;
    }


}

