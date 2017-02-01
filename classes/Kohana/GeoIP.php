<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana GeoIP Wrapper via MaxMind DB Database
 * Please install MaxMind-DB-Reader using composer or install C-extension
 * @url https://github.com/maxmind/MaxMind-DB-Reader-php
 */
class Kohana_GeoIP {
	
	protected $_db;
	protected $_ip;
	protected $_result;
	
	CONST LANG = 'en';
	
	public function __construct ($ip, $db = 'city')
	{
		if ( ! class_exists('\MaxMind\Db\Reader'))
			throw new Kohana_Exception('Maxmind Reader not found');
		
		$this->_db = Kohana::$config->load('geoip');
		$this->_ip = $ip;
		
		if (in_array($db, array('city', 'country')))
			$this->_read($db);
	}
	
	public static function factory ($ip, $db = 'city')
	{
		return new Kohana_GeoIP($ip, $db);
	}
	
	// public getters
	
	/**
	 * Get raw data
	 * @return mixed
	 */
	public function raw ()
	{
		return $this->_result;
	}
	
	/**
	 * Get raw country data
	 * @return mixed
	 */
	public function get_country ()
	{
		return $this->_get('country');
	}
	
	/**
	 * Get country code
	 * @return mixed
	 */
	public function get_country_code ()
	{
		return $this->_get('country.iso_code');
	}
	
	/**
	 * Get country iso code
	 * @param string $lang
	 * @return mixed
	 */
	public function get_country_name ($lang = Kohana_GeoIP::LANG)
	{
		return $this->_get('country.names'.'.'.$lang);
	}
	
	/**
	 * Get raw continent data
	 * @return mixed
	 */
	public function get_continent ()
	{
		return $this->_get('continent');
	}
	
	/**
	 * Get continent name
	 * @param string $lang
	 * @return mixed
	 */
	public function get_continent_name ($lang = Kohana_GeoIP::LANG)
	{
		return $this->_get('continent.names'.'.'.$lang);
	}
	
	/**
	 * Get city name
	 * @param string $lang
	 * @return mixed
	 */
	public function get_city_name ($lang = Kohana_GeoIP::LANG)
	{
		return $this->_get('city.names'.'.'.$lang);
	}
	
	/**
	 * Read MaxMind database
	 * @param string $name
	 * @return $this
	 * @throws Kohana_Exception
	 */
	protected function _read ($name = 'city')
	{
		$db = Arr::get($this->_db, $name);
		
		if ( !$db)
			throw new Kohana_Exception ('Maxmind db file :db no found', array (':db'=>$name));
		
		$reader = new \MaxMind\Db\Reader($db);
		$this->_result = $reader->get($this->_ip);
		$reader->close();
		
		return $this;
	}
	
	/**
	 * Getter
	 * @param $path
	 * @return mixed
	 */
	protected function _get ($path)
	{
		return Arr::path($this->_result, $path);
	}
}