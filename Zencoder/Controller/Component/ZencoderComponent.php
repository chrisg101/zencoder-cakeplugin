<?php
//include_once because below is not working for 2.0
//App::import('Vendor', 'Services_Zencoder', array('file'=>'zencoder-php/Zencoder.php'));
include_once(App::pluginPath('Zencoder').DS.'Vendor'.DS.'zencoder-php'.DS.'Zencoder.php');

class ZencoderComponent extends Component {
    var $test = 'true';
    
    /**
    * Encodes a video based from the $param data passed and returns a result object
    *   Example:
    *         
    *         $param = array(
    *              "input" => "http://yoursite/sample-movie.m4v",
    *              "outputs" => array(
    *                  array(
    *                      "label" => "sample-movie",
    *                      "notifications" => "http://yoursite/videos/notifications"
    *                  )
    *              )
    *          );
    *
    * $result = $this->Zencoder->encode($param);
    * //to get the output id use: $result->outputs['(the label name used ex. sample-movie)']->id
    * $outputID = $result->outputs['sample-movie']->id;
    * //to get the Job ID use
    * $jobID = $result->id;
    *
    * @param array $param
    * @return object
    */
    public function encode($param) {
        $response = null;
        $url = null;

        try {
            if (!empty($param)) {
                $apiKey = Configure::read('ZencoderAPIKey');
                $zencoder = new Services_Zencoder($apiKey);
                return $zencoder->jobs->create($param);
            }
        }
        catch (Services_Zencoder_Exception $e) {
            //return null;
        }

        return null;
    }

    /**
     * Returns an object that contains the detailed info of the output id
     * @param string $id The output id
     * @return object
     */
    public function details($id) {
        try {
            $apiKey = Configure::read('ZencoderAPIKey');
            $zencoder = new Services_Zencoder($apiKey);
            return $zencoder->outputs->details($id);
        }
        catch (Services_Zencoder_Exception $e) {
            //return null;
        }
        
        return null;
    }

    /**
     * Returns an object that contains the current state of the output id
     * @param string $id The output id
     * @return object
     */
    public function progress($id) {
        try {
            $apiKey = Configure::read('ZencoderAPIKey');
            $zencoder = new Services_Zencoder($apiKey);
            return $zencoder->outputs->progress($id);
        }
        catch (Services_Zencoder_Exception $e) {
            //return null;
        }

        return null;
    }

    /**
     * Performs the parsing of the zencoder notification passed and returns an array of the results from the encoding
     * to get the encoded video for example:
     *      
     *      $output = $this->Zencoder->parseIncoming();
     *      $videoUrl = $output['output']['url'];
     * 
     * call this function from a public accessible url ex. http://yourpublicsite.com/scripts/notification
     * @return array
     */
    public function parseIncoming() {
        try {
            $apiKey = Configure::read('ZencoderAPIKey');
            $zencoder = new Services_Zencoder($apiKey);
            return $zencoder->notifications->parseIncoming();
        }
        catch (Services_Zencoder_Exception $e) {
            //return null;
        }

        return null;
    }
}