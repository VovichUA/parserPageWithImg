<?php

namespace Parser;

class CliPrinter{
    /**
     * @param $message
     */
    public function out($message){
        echo $message;
    }

    public function newline(){
        $this->out("\n");
    }

    /**
     * @param $message
     */
    public function display($message){
        $this->newline();
        $this->out($message);
        $this->newline();
        $this->newline();
    }

    /**
     * @param $url
     * @return string
     */
    public function checkUrl($url){
        if (parse_url($url,PHP_URL_SCHEME) === null){
            $url = 'HTTP://'.$url;
        }
        if (get_headers($url,1)) {
            return $url;
        } else {
            $this->display('WRONG URL!!!\n');
            exit();
        }
    }

    /**
     * @param $url
     * @return array
     */
    public function arrayWithImage($url){
        $homepage = file_get_contents($url);
        $arrayFromThisPage = [];
        preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $homepage, $media);
        unset($homepage);
        $homepage = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);
        foreach ($homepage as $item){
            $info = pathinfo($item);
            if (isset($info['extension'])) {
                if (($info['extension'] == 'jpg') ||
                    ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') ||
                    ($info['extension'] == 'png'))
                    $arrayFromThisPage[]=$item;
            }
        }
        return $arrayFromThisPage;
    }

    public function pageToString($url){
        if ($url == " "){
            exit($this->display("parse [ url ] - for parse page\n"));
        }
        $url = $this->checkUrl($url);
        $arrayFromThisPage[] = $this->arrayWithImage($url);
        if (file_exists('uploads') == false) {
            mkdir('uploads');
        }
        $fp = fopen('uploads/file.csv', 'w');
        fclose($fp);

        $current = "Страница исходник - ".$url.";\n";
        foreach ($arrayFromThisPage[0] as $fields) {
            $current .= $fields.";\n";
        }

        file_put_contents('uploads/file.csv',$current);
        $this->display('FILE in directory uploads');
    }

    public function showInfo($url){
        if ($url == " "){
            exit($this->display("report [ url ] - for parse page\n"));
        }
        $url = $this->checkUrl($url);
        $arrayFromThisPage[] = $this->arrayWithImage($url);
        foreach($arrayFromThisPage[0] as $item){
            $this->out($item."\n");
        }
//        print_r($arrayFromThisPage);
    }



}