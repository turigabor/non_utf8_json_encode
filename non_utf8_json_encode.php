<?php
/**
 * Returns the JSON representation of a value
 * @param mixed $value
 * @return string
 */
function non_utf8_json_encode ($value) {
	if (is_null($value)) {
		return 'null';
	}
	if (is_bool($value)) {
		return $value ? 'true' : 'false';
	}
	if (is_string($value)) {
		$replace = array(
			'\\' => '\\\\',
			'/' => '\/',
			'"' => '\"',
			"\n" => '\n',
			"\r" => '\r',
			"\t" => '\t'
		);
		return '"' . strtr($value, $replace) . '"';
	}
	if (is_numeric($value)) {
		return $value;
	}
	if (is_array($value)) {
		$isRealArray = true;
		foreach (array_keys($value) as $index => $key) {
			if ($index !== $key) {
				$isRealArray = false;
				break;
			}
		}
		$items = array();
		if ($isRealArray) {
			foreach ($value as $value) {
				$items[] = non_utf8_json_encode($value);
			}
			return '[' . join(',', $items) . ']';
		}
		foreach ($value as $key => $value) {
			$items[] = '"' . $key . '":' . non_utf8_json_encode($value);
		}
		return '{' . join(',', $items) . '}';
	}
	if (is_object($value)) {
		$items = array();
		foreach ($value as $key => $value) {
			$items[] = '"' . $key . '":' . non_utf8_json_encode($value);
		}
		return '{' . join(',', $items) . '}';
	}
	throw new InvalidArgumentException('unknown input: ' . var_export($value, true));
}