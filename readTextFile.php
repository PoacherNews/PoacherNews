<?php
	$files = empty($_FILES['document'] ? '' : $_FILES['document']);

	if(isset($files)) {
		readTextFile();
	} else {
		print "File has not been selected";
	}
	
	function readTextFile() {
		if (isset( $_FILES['document'])) {
			// Upload .docx, .doc, .txt
			$app_docx = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
			$app_doc = "application/msword";
			$text_plain = "text/plain";
			switch($_FILES['document']['type']) {
				case $app_docx: // Fix line break before paragraph starts
					$source_file = $_FILES['document']['tmp_name'];
					$zip = new ZipArchive; // Parse word docx by opening up zip file then display it
					$dataFile = 'word/document.xml';
					if (true === $zip->open($source_file)) { // Open the archive file
						if (($index = $zip->locateName($dataFile)) !== false) { // If true, search for the data file in archive
							$data = $zip->getFromIndex($index);
							$zip->close();
							$dom = new DOMDocument;
							$dom->loadXML($data, LIBXML_NOENT
								| LIBXML_XINCLUDE
								| LIBXML_NOERROR
								| LIBXML_NOWARNING);
							$xmldata = $dom->saveXML();
							$contents = $xmldata;
							// TODO: V 2.10
                            /*
							$contents = strip_tags($xmldata, '<w:p><w:u><w:i><w:b><w:r>'); // Strip the p, u, i, b, and r tags
							$contents = preg_replace("/(<(\/?)w:(.)[^>]*>)\1*[DELETE THIS]/", "<$2$3>", $contents);
							$dom = new DOMDocument;
							// @$dom->loadHTML($contents, LIBXML_HTML_NOIMPLIED  | LIBXML_HTML_NODEFDTD);
                            $contents = $dom->saveHTML();
                            */
							// Get rid of weird special chars
							$find = array('&acirc;&#128;&#156;', '&acirc;&#128;&#157;', '&acirc;&#128;&#152;', '&acirc;&#128;&#153;', '&acirc;&#128;&brvbar;', '&acirc;&#128;&#147;', '&acirc;&#128;&#148;');
							$replace = array('“', '”', "‘", "’", "...", "–", "—");
						
							$contents = str_replace($find, $replace, $contents);
							print ($contents);
						}
					}
					break;

				case $app_doc:
					$source_file = $_FILES['document']['tmp_name'];
					if(file_exists($source_file)) {
						if(($fh = fopen($source_file, 'r')) !== false ) {
							$headers = fread($fh, 0xA00);
							$n1 = ( ord($headers[0x21C]) - 1 );
							$n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 ); 
							$n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );
							$n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );
							$textLength = ($n1 + $n2 + $n3 + $n4);
							$extracted_plaintext = fread($fh, $textLength);
                            
							$extracted_plaintext = (strip_tags($extracted_plaintext,’‘));
							$map = array(chr(0x8A) => chr(0xA9), chr(0x8C) => chr(0xA6),
										chr(0x8D) => chr(0xAB), chr(0x8E) => chr(0xAE),
										chr(0x8F) => chr(0xAC), chr(0x9C) => chr(0xB6),
										chr(0x9D) => chr(0xBB), chr(0xA1) => chr(0xB7),
										chr(0xA5) => chr(0xA1), chr(0xBC) => chr(0xA5),
										chr(0x9F) => chr(0xBC), chr(0xB9) => chr(0xB1),
										chr(0x9A) => chr(0xB9), chr(0xBE) => chr(0xB5),
										chr(0x9E) => chr(0xBE), chr(0x80) => '&euro;',
										chr(0x82) => '&sbquo;', chr(0x84) => '&bdquo;',
										chr(0x85) => '&hellip;', chr(0x86) => '&dagger;',
										chr(0x87) => '&Dagger;', chr(0x89) => '&permil;',
										chr(0x8B) => '&lsaquo;', chr(0x91) => '&lsquo;',
										chr(0x92) => '&rsquo;', chr(0x93) => '&ldquo;',
										chr(0x94) => '&rdquo;', chr(0x95) => '&bull;',
										chr(0x96) => '&ndash;', chr(0x97) => '&mdash;',
										chr(0x99) => '&trade;', chr(0x9B) => '&rsquo;',
										chr(0xA6) => '&brvbar;', chr(0xA9) => '&copy;',
										chr(0xAB) => '&laquo;', chr(0xAE) => '&reg;',
										chr(0xB1) => '&plusmn;', chr(0xB5) => '&micro;',
										chr(0xB6) => '&para;', chr(0xB7) => '&middot;',
										chr(0xBB) => '&raquo;');
							$result = html_entity_decode(mb_convert_encoding(strtr($extracted_plaintext, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');

							print nl2br($result);
                            
						} else {
							return false;
						}
					} else {
						return false;
					}
					break;

				case $text_plain: // Good
					$source_file = $_FILES['document']['tmp_name'];
					$body = file_get_contents($source_file);
					$map = array(
								chr(0x8A) => chr(0xA9), chr(0x8C) => chr(0xA6),
								chr(0x8D) => chr(0xAB), chr(0x8E) => chr(0xAE),
								chr(0x8F) => chr(0xAC), chr(0x9C) => chr(0xB6),
								chr(0x9D) => chr(0xBB), chr(0xA1) => chr(0xB7),
								chr(0xA5) => chr(0xA1), chr(0xBC) => chr(0xA5),
								chr(0x9F) => chr(0xBC), chr(0xB9) => chr(0xB1),
								chr(0x9A) => chr(0xB9), chr(0xBE) => chr(0xB5),
								chr(0x9E) => chr(0xBE), chr(0x80) => '&euro;',
								chr(0x82) => '&sbquo;', chr(0x84) => '&bdquo;',
								chr(0x85) => '&hellip;', chr(0x86) => '&dagger;',
								chr(0x87) => '&Dagger;', chr(0x89) => '&permil;',
								chr(0x8B) => '&lsaquo;', chr(0x91) => '&lsquo;',
								chr(0x92) => '&rsquo;', chr(0x93) => '&ldquo;',
								chr(0x94) => '&rdquo;', chr(0x95) => '&bull;',
								chr(0x96) => '&ndash;', chr(0x97) => '&mdash;',
								chr(0x99) => '&trade;', chr(0x9B) => '&rsquo;',
								chr(0xA6) => '&brvbar;', chr(0xA9) => '&copy;',
								chr(0xAB) => '&laquo;', chr(0xAE) => '&reg;',
								chr(0xB1) => '&plusmn;', chr(0xB5) => '&micro;',
								chr(0xB6) => '&para;', chr(0xB7) => '&middot;',
								chr(0xBB) => '&raquo;', chr(0xd4) => '&lsquo;',
								chr(0xd5) => '&rsquo;', chr(0xd2) => '&ldquo;',
								chr(0xd3) => '&rdquo;', chr(0xd0) => '&ndash;',
								chr(0xd1) => '&mdash;', chr(0xc9) => '&hellip;',
							);
					$contents = html_entity_decode(mb_convert_encoding(strtr($body, $map), 'UTF-8', 'ISO-8859-1'), ENT_QUOTES, 'UTF-8');
					
					$find = array('&acirc;&#128;&#156;', '&acirc;&#128;&#157;', '&acirc;&#128;&#152;', '&acirc;&#128;&#153;', '&acirc;&#128;&brvbar;', '&acirc;&#128;&#147;', '&acirc;&#128;&#148;');
				    $replace = array('“', '”', "‘", "’", "...", "–", "—");
						
                    $result = str_replace($find, $replace, $contents);
                    print nl2br($result);
					break;

				default:
					print "<script language='javascript'>alert('Invalid input. Please try again');</script>";
					break;
			}
		}
	  }// End of readTextFile
?>