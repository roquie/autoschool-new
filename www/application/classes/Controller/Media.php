<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Media extends Controller
{

    public function action_download()
    {
        $path = $this->request->param('path');

        $this->response->send_file(APPPATH.'download/'.$path);
    }

    public function action_load()
    {
        // Get the file path from the request
        $file = $this->request->uri();

        // Find the file extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        // Remove the extension from the filename
        $file = substr($file, 0, -(strlen($ext) + 1));

        if ($file = Kohana::find_file('media', $file, $ext))
        {
            // Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
            $this->check_cache(sha1($this->request->uri()).filemtime($file));

            // Send the file content as the response
            $this->response->body(file_get_contents($file));

            // Set the proper headers to allow caching
            $this->response->headers('content-type',  File::mime_by_ext($ext));
            $this->response->headers('last-modified', date('r', filemtime($file)));
        }
        else
        {
            // Return a 404 status
            $this->response->status(404);
        }
    }




}
