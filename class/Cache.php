<?php

namespace App;

class Cache {

	public $dirname = CACHE_PATH;
	public $filename;
	public $file;
	public $duration; // In minutes

	private $buffer = true;

	public function __construct( $filename, $duration ) {

		$this->filename = $filename;
		$this->duration = $duration;

		if ( ! is_dir( $this->dirname ) ) {
			mkdir( $this->dirname );
		}

		$this->file = $this->dirname . $this->filename;
	}

	/**
	 * Read from cache file
	 * @return bool|false|string
	 */
	public function read() {

		if ( file_exists( $this->file ) ) {
			$lifetime = ( time() - filemtime( $this->file ) ) / 60;
			if ( $lifetime > $this->duration ) {
				return false;
			}

			return file_get_contents( $this->file );
		}

		return false;
	}

	/**
	 * write in cache file
	 *
	 * @param $content
	 */
	public function write( $content ) {
		file_put_contents( $this->file, $content );
	}

	/**
	 * Starting write in cache file if APPOE isn't in maintenance mode
	 * @return bool
	 */
	public function start() {

		$AppConfig = new AppConfig();
		if ( 'true' === $AppConfig->get( 'options', 'maintenance' ) ) {
			$this->buffer = false;

			return false;
		}

		if ( $content = $this->read() ) {
			echo $content;
			$this->buffer = false;

			return true;
		}
		ob_start();

		return false;
	}

	/**
	 * end writing in cache file
	 * @return bool
	 */
	public function end() {
		if ( ! $this->buffer ) {
			return false;
		}
		$content = ob_get_clean();
		echo $content;
		$this->write( $content );

		return true;
	}

	/**
	 * delete cache file
	 */
	public function delete() {

		if ( file_exists( $this->file ) ) {
			unlink( $this->file );
		}
	}

	/**
	 * delete all cache files
	 */
	public function clear() {

		if ( is_dir( $this->dirname ) ) {
			$files = glob( $this->dirname . '*' );
			foreach ( $files as $file ) {
				unlink( $file );
			}
		}
	}
}