<?php
namespace Parser;

class App {

    protected $printer;

    protected $registry = [];

    /**
     * App constructor.
     */
    public function __construct(){
        $this->printer = new CliPrinter();
    }

    /**
     * @return CliPrinter
     */
    public function getPrinter(){
        return $this->printer;
    }

    /**
     * @param $name
     * @param $callable
     */
    public function registerCommand($name, $callable){
        $this->registry[$name] = $callable;
    }

    /**
     * @param $command
     * @return mixed|null
     */
    public function getCommand($command){
        return isset($this->registry[$command]) ? $this->registry[$command] : null;
    }

    /**
     * @param array $argv
     */
    public function runCommand(array $argv = []){
        $command_name = "help";

        if (isset($argv[1])) {
            $command_name = $argv[1];
        }

        $command = $this->getCommand($command_name);

        if ($command === null) {
            $this->getPrinter()->display("ERROR: Command \"$command_name\" not found.");
            exit();
        }
        call_user_func($command, $argv);
    }

    public function helpInfo(){
        $this->getPrinter()->display("usage:\n
        help - for show commands\n 
        parse [ url ] - for parse page\n  
        report [ url ] - for show detail info in console");
    }
}