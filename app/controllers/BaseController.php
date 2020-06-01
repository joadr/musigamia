<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function get_url_contents($link){
	    $crl = curl_init();
	    $timeout = 25;
	    curl_setopt ($crl, CURLOPT_URL, $link);
	    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	    curl_setopt($crl, CURLOPT_HTTPHEADER, array('User-Agent: Goear 1.3 (iPod touch; iPhone OS 5.1.1;)'));
	    $ret = curl_exec($crl);
	    curl_close($crl);
	    return $ret;
	}

}