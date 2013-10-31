<?php

define('DIRECTION_UP', 0);
define('DIRECTION_RIGHT', 1);
define('DIRECTION_DOWN', 2);
define('DIRECTION_LEFT', 3);

/*
 ����
  -
- 0 -
  -
*/

class BrainLoller {
	protected $picture;	// �������� BrainLoller
	protected $pointer = DIRECTION_RIGHT; // ��������� ���� ���� �� ��������� ���� (0 = 'up', 1 = 'right', 2 = 'down', 3 = 'left')

	function __construct($picture){
		// ��������� ���������� �����������
		$this->picture = imagecreatefrompng($picture);
		
		// �������� ������ �����������
		$size = getimagesize($picture);
		$this->size['w'] = $size[0];
		$this->size['h'] = $size[1];
	}

	public function getCode(){
		$current_pixel = array(0, 0);	// ������� ������� (x, y)
		$this->pointer = DIRECTION_RIGHT;
		$code = '';
		for(;;){
			if($current_pixel[0] > $this->size['w'] or $current_pixel[1] > $this->size['h'] or $current_pixel[0] < 0 or $current_pixel[1] < 0) break; // ���� ����� �� ������� �� ��������� ����

			$pixel_color = imagecolorat($this->picture, $current_pixel[0], $current_pixel[1]);	// ����� ���� �������
			$pixel_color = array(
				( ($pixel_color >> 16) & 0xFF ),	// Red
				( ($pixel_color >> 8) & 0xFF ),		// Green
				( $pixel_color & 0xFF ),			// Blue
			);
			
			// ��������� ����� ������� ����������� ������ ����, ���� ������� �� ������������, ���� ������� �� ��������� � $code
			switch($pixel_color){
				case array(0, 255, 0):
					// +
					$code .= '+';
					break;

				case array(0, 128, 0):
					// -
					$code .= '-';
					break;

				case array(255, 0, 0):
					// >
					$code .= '>';
					break;

				case array(128, 0, 0):
					// <
					$code .= '<';
					break;

				case array(255, 255, 0):
					// [
					$code .= '[';
					break;

				case array(128, 128, 0):
					// ]
					$code .= ']';
					break;

				case array(0, 0, 255):
					// .
					$code .= '.';
					break;

				case array(0, 0, 128):
					// ,
					$code .= ',';
					break;

				case array(0, 255, 255):
					// <-
					if($this->pointer + 1 > DIRECTION_LEFT) $this->pointer = 0;
					else $this->pointer += 1;
					break;

				case array(0, 128, 128):
					// ->
					if($this->pointer - 1 < DIRECTION_UP) $this->pointer = 3;
					else $this->pointer -= 1;
					break;
			}
			
			switch($this->pointer){
				case DIRECTION_UP:
					$current_pixel[1] -= 1;
					break;

				case DIRECTION_RIGHT:
					$current_pixel[0] += 1;
					break;

				case DIRECTION_DOWN:
					$current_pixel[1] += 1;
					break;

				case DIRECTION_LEFT:
					$current_pixel[0] -= 1;
					break;
			}
		}

		return $code;
	}

	function __destruct(){
		imagedestroy($this->picture);	// ������������ ������� �������
	}
}