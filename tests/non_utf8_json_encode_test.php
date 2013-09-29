<?php
include '../non_utf8_json_encode.php';

class NonUtf8JsonEncode_TestCase extends PHPUnit_Framework_TestCase {
	public function test_compare_with_original_encode () {
		$mixed = array(
			'a' => 'abcdefg...',
			'b' => [0, 1, 2, 3, 4],
			'c' => null,
			'd' => array('f' => false, 't' => true),
			'e' => 1/3,
			'f' => ['"', "'", '\\', '/', "\n", "\r", "\t"]
		);
		$this->assertEquals(json_encode($mixed), non_utf8_json_encode($mixed));

		$object = new stdClass();
		$object->a = 'abc';
		$object->b = 123;
		$this->assertEquals(json_encode($object), non_utf8_json_encode($object));
	}
	public function test_original_encode_is_wrong () {
		$str = $this->get_latin2_string();
		$this->assertEquals('null', json_encode($str));
		$this->assertEquals(JSON_ERROR_UTF8, json_last_error());
	}
	public function test_non_utf8_json_encode_is_good () {
		$str = $this->get_latin2_string();
		$this->assertEquals('"' . $str . '"', non_utf8_json_encode($str));
	}
	private function get_latin2_string () {
		$str = 'Árvíztűrő tükörfúrógép';
		return iconv('UTF-8', 'ISO-8859-2', $str);
	}
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_unkown_type () {
		$resource = fopen(__FILE__, 'r');
		non_utf8_json_encode($resource);
	}
}