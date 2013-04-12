<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* class psd reader */

class PhpPsdReader {
	var $infoArray;
	var $fp;
	var $fileName;
	var $tempFileName;
	var $colorBytesLength;

	function PhpPsdReader($fileName) {
		set_time_limit(0);
		$this->infoArray = array();
		$this->fileName = $fileName;
		$this->fp = fopen($this->fileName,'r');

		if (fread($this->fp,4)=='8BPS') {
			$this->infoArray['version id'] = $this->_getInteger(2);
			fseek($this->fp,6,SEEK_CUR); // 6 bytes of 0's
			$this->infoArray['channels'] = $this->_getInteger(2);
			$this->infoArray['rows'] = $this->_getInteger(4);
			$this->infoArray['columns'] = $this->_getInteger(4);
			$this->infoArray['colorDepth'] = $this->_getInteger(2);
			$this->infoArray['colorMode'] = $this->_getInteger(2);


			/* COLOR MODE DATA SECTION */ //4bytes Length The length of the following color data.
			$this->infoArray['colorModeDataSectionLength'] = $this->_getInteger(4);
			fseek($this->fp,$this->infoArray['colorModeDataSectionLength'],SEEK_CUR); // ignore this snizzle

			/*  IMAGE RESOURCES */
			$this->infoArray['imageResourcesSectionLength'] = $this->_getInteger(4);
			fseek($this->fp,$this->infoArray['imageResourcesSectionLength'],SEEK_CUR); // ignore this snizzle

			/*  LAYER AND MASK */
			$this->infoArray['layerMaskDataSectionLength'] = $this->_getInteger(4);
			fseek($this->fp,$this->infoArray['layerMaskDataSectionLength'],SEEK_CUR); // ignore this snizzle


			/*  IMAGE DATA */
			$this->infoArray['compressionType'] = $this->_getInteger(2);
			$this->infoArray['oneColorChannelPixelBytes'] = $this->infoArray['colorDepth']/8;
			$this->colorBytesLength = $this->infoArray['rows']*$this->infoArray['columns']*$this->infoArray['oneColorChannelPixelBytes'];

			if ($this->infoArray['colorMode']==2) {
				$this->infoArray['error'] = 'images with indexed colours are not supported yet';
				return false;
			}
		} else {
			$this->infoArray['error'] = 'invalid or unsupported psd';
			return false;
		}
	}


	function getImage() {
		// decompress image data if required
		switch($this->infoArray['compressionType']) {
			// case 2:, case 3: zip not supported yet..
			case 1:
				// packed bits
				$this->infoArray['scanLinesByteCounts'] = array();
				for ($i=0; $i<($this->infoArray['rows']*$this->infoArray['channels']); $i++) $this->infoArray['scanLinesByteCounts'][] = $this->_getInteger(2);
				$this->tempFileName = tempnam(realpath('/tmp'),'decompressedImageData');
				$tfp = fopen($this->tempFileName,'wb');
				foreach ($this->infoArray['scanLinesByteCounts'] as $scanLinesByteCount) {
					fwrite($tfp,$this->_getPackedBitsDecoded(fread($this->fp,$scanLinesByteCount)));
				}
				fclose($tfp);
				fclose($this->fp);
				$this->fp = fopen($this->tempFileName,'r');
			default:
				// continue with current file handle;
				break;
		}

		// let's write pixel by pixel....
		$image = imagecreatetruecolor($this->infoArray['columns'],$this->infoArray['rows']);

		for ($rowPointer = 0; ($rowPointer < $this->infoArray['rows']); $rowPointer++) {
			for ($columnPointer = 0; ($columnPointer < $this->infoArray['columns']); $columnPointer++) {
				/* 	The color mode of the file. Supported values are: Bitmap=0;
					Grayscale=1; Indexed=2; RGB=3; CMYK=4; Multichannel=7;
					Duotone=8; Lab=9.
				*/
				switch ($this->infoArray['colorMode']) {
					case 2: // indexed... info should be able to extract from color mode data section. not implemented yet, so is grayscale
						exit;
						break;
					case 0:
						// bit by bit
						if ($columnPointer == 0) $bitPointer = 0;
						if ($bitPointer==0) $currentByteBits = str_pad(base_convert(bin2hex(fread($this->fp,1)), 16, 2),8,'0',STR_PAD_LEFT);
						$r = $g = $b = (($currentByteBits[$bitPointer]=='1')?0:255);
						$bitPointer++;
						if ($bitPointer==8) $bitPointer = 0;
						break;

					case 1:
					case 8: // 8 is indexed with 1 color..., so grayscale
						$r = $g = $b = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						break;

					case 4: // CMYK
						$c = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						$currentPointerPos = ftell($this->fp);
						fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
						$m = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
						$y = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
						$k = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						fseek($this->fp,$currentPointerPos);
						$r = round(($c * $k) / (pow(2,$this->infoArray['colorDepth'])-1));
						$g = round(($m * $k) / (pow(2,$this->infoArray['colorDepth'])-1));
						$b = round(($y * $k) / (pow(2,$this->infoArray['colorDepth'])-1));

  						break;

  						case 9: // hunter Lab
  							// i still need an understandable lab2rgb convert algorithm... if you have one, please let me know!
							$l = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
							$currentPointerPos = ftell($this->fp);
							fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
							$a = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
							fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
							$b =  $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
							fseek($this->fp,$currentPointerPos);

							$r = $l;
							$g = $a;
							$b = $b;

						break;
					default:
						$r = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						$currentPointerPos = ftell($this->fp);
						fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
						$g = $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						fseek($this->fp,$this->colorBytesLength-1,SEEK_CUR);
						$b =  $this->_getInteger($this->infoArray['oneColorChannelPixelBytes']);
						fseek($this->fp,$currentPointerPos);
						break;

				}

				if (($this->infoArray['oneColorChannelPixelBytes']==2)) {
					$r = $r >> 8;
					$g = $g >> 8;
					$b = $b >> 8;
				} elseif (($this->infoArray['oneColorChannelPixelBytes']==4)) {
					$r = $r >> 24;
					$g = $g >> 24;
					$b = $b >> 24;
				}

				$pixelColor = imagecolorallocate($image,$r,$g,$b);
				imagesetpixel($image,$columnPointer,$rowPointer,$pixelColor);
			}
		}
		fclose($this->fp);
		if (isset($this->tempFileName)) unlink($this->tempFileName);
		return $image;
	}

	/**
	 *
	 * PRIVATE FUNCTIONS
	 *
	 */

	function _getPackedBitsDecoded($string) {
		/*
		The PackBits algorithm will precede a block of data with a one byte header n, where n is interpreted as follows:
		n Meaning
		0 to 127 Copy the next n + 1 symbols verbatim
		-127 to -1 Repeat the next symbol 1 - n times
		-128 Do nothing

		Decoding:
		Step 1. Read the block header (n).
		Step 2. If the header is an EOF exit.
		Step 3. If n is non-negative, copy the next n + 1 symbols to the output stream and go to step 1.
		Step 4. If n is negative, write 1 - n copies of the next symbol to the output stream and go to step 1.

		*/

		$stringPointer = 0;
		$returnString = '';

		while (1) {
			if (isset($string[$stringPointer])) $headerByteValue = $this->_unsignedToSigned(hexdec(bin2hex($string[$stringPointer])),1);
			else return $returnString;
			$stringPointer++;

			if ($headerByteValue >= 0) {
				for ($i=0; $i <= $headerByteValue; $i++) {
					$returnString .= $string[$stringPointer];
					$stringPointer++;
				}
			} else {
				if ($headerByteValue != -128) {
					$copyByte = $string[$stringPointer];
					$stringPointer++;

					for ($i=0; $i < (1-$headerByteValue); $i++) {
						$returnString .= $copyByte;
					}
				}
			}
		}
	}

	function _unsignedToSigned($int,$byteSize=1) {
		switch($byteSize) {
			case 1:
				if ($int<128) return $int;
				else return -256+$int;
				break;

			case 2:
				if ($int<32768) return $int;
				else return -65536+$int;

			case 4:
				if ($int<2147483648) return $int;
				else return -4294967296+$int;

			default:
				return $int;
		}
	}

	function _hexReverse($hex) {
		$output = '';
		if (strlen($hex)%2) return false;
		for ($pointer = strlen($hex);$pointer>=0;$pointer-=2) $output .= substr($hex,$pointer,2);
		return $output;
	}

	function _getInteger($byteCount=1) {
		switch ($byteCount) {
			case 4:
				// for some strange reason this is still broken...
				return @reset(unpack('N',fread($this->fp,4)));
				break;

			case 2:
				return @reset(unpack('n',fread($this->fp,2)));
				break;

			default:
				return hexdec($this->_hexReverse(bin2hex(fread($this->fp,$byteCount))));
		}
	}
}

/* class main */

class Image_reader{

	var $src		= ''; //源文件路径
	var $dest		= ''; //目标地址
	var $type 		= 'jpg';
	var $jpgQuality	= 100;

	public function __construct($params = array()){
		if (count($params) > 0){
			$this->init($params);
		}
	}
	
	public function init($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val){
				if (isset($this->$key)){
					$this->$key = $val;
				}
			}
		}
	}

	public function read(){
		$ext = strtolower(substr($this->src,-3));
		if($ext == 'psd') return $this->psd_reader();
		elseif($ext == 'bmp') return $this->bmp_reader();
		else return false; 
	}

	public function write(){
		$img = $this->read();
		$type = $this->type;
		if($img){
			if($type == 'jpg') imagejpeg($img,$this->dest,$this->jpgQuality);
			elseif($type == 'png') imagepng($img);
			return true;
		}else{
			return false;
		}
	}

	private function psd_reader(){
		$fileName = $this->src;
		$psdReader = new PhpPsdReader($fileName);
		if (isset($psdReader->infoArray['error'])) return '';
		else return $psdReader->getImage();
	}

	private function bmp_reader(){

		$p_sFile = $this->src;

		//    Load the image into a string 
        $file    =    fopen($p_sFile,"rb"); 
        $read    =    fread($file,10); 
        while(!feof($file)&&($read<>"")) 
            $read    .=    fread($file,1024); 
        
        $temp    =    unpack("H*",$read); 
        $hex    =    $temp[1]; 
        $header    =    substr($hex,0,108); 
        
        //    Process the header 
        //    Structure: http://www.fastgraph.com/help/bmp_header_format.html 
        if (substr($header,0,4)=="424d") 
        { 
            //    Cut it in parts of 2 bytes 
            $header_parts    =    str_split($header,2); 
            
            //    Get the width        4 bytes 
            $width            =    hexdec($header_parts[19].$header_parts[18]); 
            
            //    Get the height        4 bytes 
            $height            =    hexdec($header_parts[23].$header_parts[22]); 
            
            //    Unset the header params 
            unset($header_parts); 
        } 
        
        //    Define starting X and Y 
        $x                =    0; 
        $y                =    1; 
        
        //    Create newimage 
        $image            =    imagecreatetruecolor($width,$height); 
        
        //    Grab the body from the image 
        $body            =    substr($hex,108); 

        //    Calculate if padding at the end-line is needed 
        //    Divided by two to keep overview. 
        //    1 byte = 2 HEX-chars 
        $body_size        =    (strlen($body)/2); 
        $header_size    =    ($width*$height); 

        //    Use end-line padding? Only when needed 
        $usePadding        =    ($body_size>($header_size*3)+4); 
        
        //    Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption 
        //    Calculate the next DWORD-position in the body 
        for ($i=0;$i<$body_size;$i+=3) 
        { 
            //    Calculate line-ending and padding 
            if ($x>=$width) 
            { 
                //    If padding needed, ignore image-padding 
                //    Shift i to the ending of the current 32-bit-block 
                if ($usePadding) 
                    $i    +=    $width%4; 
                
                //    Reset horizontal position 
                $x    =    0; 
                
                //    Raise the height-position (bottom-up) 
                $y++; 
                
                //    Reached the image-height? Break the for-loop 
                if ($y>$height) 
                    break; 
            } 
            
            //    Calculation of the RGB-pixel (defined as BGR in image-data) 
            //    Define $i_pos as absolute position in the body 
            $i_pos    =    $i*2; 
            $r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]); 
            $g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]); 
            $b        =    hexdec($body[$i_pos].$body[$i_pos+1]); 
            
            //    Calculate and draw the pixel 
            $color    =    imagecolorallocate($image,$r,$g,$b); 
            imagesetpixel($image,$x,$height-$y,$color); 
            
            //    Raise the horizontal position 
            $x++; 
        } 
        
        //    Unset the body / free the memory 
        unset($body); 
        
        //    Return image-object 
        return $image; 

	}

}