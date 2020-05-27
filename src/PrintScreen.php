<?php

namespace Beetpix;

use SilverStripe\Control\Director;
use SilverStripe\Core\Environment;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\HTTPRequest;

class PrintScreen 
{
    private $options = [];

    private $result = null;

    static function inst()
    {
        return new PrintScreen;
    }

    public function getImage($target = null)
    {
        $this->options['format'] = 'image';
        if ($result = $this->screenshot($target, $this->options)) {
            return $result['url'];
        }
        return false;
    }

    public function getPDF($target = null)
    {
        $this->options['format'] = 'PDF';
        if ($result = $this->screenshot($target, $this->options)) {
            return $result['url'];
        }
        return false;
    }

    public function downloadPDF($target = null, $filename = null)
    {
        $target = $target ?: $this->getURL();
        $filename = $filename ?: preg_replace('/[^ \w]+/', '_', $target);

        if ($url = $this->getPDF($target)) {
            return HTTPRequest::send_file(file_get_contents($url), $filename . '.pdf');
        }
        return false;
    }

    public function downloadImage($target = null, $filename = null)
    {
        $target = $target ?: $this->getURL();
        $filename = $filename ?: preg_replace('/[^ \w]+/', '_', $target);        

        if ($url = $this->getImage($target)) {
            return HTTPRequest::send_file(file_get_contents($url), $filename . '.jpg');
        }
        return false;
    }

    public function width(int $value)
    {
        $this->options['width'] = $value;
        return $this;
    }

    public function height(int $value)
    {
        $this->options['height'] = $value;
        return $this;
    }

    public function scrollTo(string $value)
    {
        $this->options['scrollto'] = $value;
        return $this;
    }

    public function click(string $value)
    {
        $this->options['click'] = $value;
        return $this;
    }

    public function flush()
    {
        $this->options['flush'] = true;
        return $this;
    }

    public function fullPage()
    {
        $this->options['fullpage'] = true;
        return $this;
    }

    public function crop(int $x, int $y, int $width, int $height)
    {
        $this->options['crop'] = $x . ',' . $y . ',' . $width . ',' . $height;
        return $this;
    }

    public function device(string $value)
    {
        $this->options['device'] = $value;
        return $this;
    }

    public function element(string $value)
    {
        $this->options['element'] = $value;
        return $this;
    }

    public function paperSize(string $value)
    {
        $this->options['size'] = $value;
        return $this;
    }

    public function delay(int $value)
    {
        $this->options['delay'] = $value;
        return $this;
    }

    public function cookies(array $value)
    {
        $this->options['cookies'] = $value;
        return $this;
    }

    public function headers(array $value)
    {
        $this->options['headers'] = $value;
        return $this;
    }

    public function geolocation(float $lat, float $lng)
    {
        $this->options['geolocation'] = [
            'latitude' => $lat,
            'longitude' => $lng
        ];
        return $this;
    }

    public function disableJS()
    {
        $this->options['enablejs'] = false;
        return $this;
    }

    public function agent(string $value)
    {
        $this->options['agent'] = $value;
        return $this;
    }

    public function injectJS(string $value)
    {
        $this->options['js'] = $value;
        return $this;
    }

    public function injectCSS(string $value)
    {
        $this->options['css'] = $value;
        return $this;
    }

    public function proxy(string $value)
    {
        $this->options['proxy'] = $value;
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function isError()
    {
        return isset($this->result['error']);
    }

    public function screenshot($target = null, $options = null)
    {
        $key = Environment::getEnv('PRINTSCREENAPI_KEY') ?: Config::inst()->get(self::class, 'api_key');
        if (!$key) {
            throw new \Exception('You must either add `PRINTSCREENAPI_KEY` to your environment or set `api_key` for ' . self::class);
        }

        $data = [
            'key' => $key,
            'target' => $target ?: Director::protocolAndHost() . $_SERVER['REQUEST_URI']
        ];

        if ($options) {
            $data = array_merge($options, $data);
        }

        $ch = curl_init('https://api.printscreenapi.com');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->result = json_decode($result, true);

        if ((int)$code !== 200) {
            return false;
        }
        return $this->result;
    }

    private function getURL()
    {
        if ($controller = Controller::curr()) {
            if ($request = $controller->getRequest()) {
                return $request->getURL(true);
            }
        }        
    }


}