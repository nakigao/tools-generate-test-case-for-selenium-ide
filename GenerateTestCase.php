<?php

class GenerateTestCase
{
	public function __construct($options = array())
	{
		// nothing to do.
	}

	public function html($fileName = "")
	{
		if (empty($fileName)) {
			throw new Exception("No specific file name.");
		}

		$filePointer = fopen($fileName, 'r');
		while ($line = fgetcsv($filePointer)) {
			if (mb_substr($line[0], 0, 1) == '#') {
				// 先頭１文字が「#」の場合は、コメント行なので読み飛ばす
				continue;
			}
			$result = "";
			$result .= <<< EOM
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://selenium-ide.openqa.org/profiles/test-case">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="selenium.base" href="{$line[0]}" />
<title>{$line[1]}</title>
</head>
<body>
<table cellpadding="1" cellspacing="1" border="1">
<thead>
<tr><td rowspan="1" colspan="3">{$line[1]}</td></tr>
</thead><tbody>
EOM;

			$result .= <<< EOM
<tr>
	<td>open</td>
	<td>{$line[2]}</td>
	<td></td>
</tr>
<tr>
	<td>assertNotBodyText</td>
	<td>regexp:(Notice)|(Warning)|(Error)</td>
	<td></td>
</tr>

EOM;
			$result .= <<< EOM
</tbody></table>
</body>
</html>
EOM;
			// write file.
			$writeFilePointer = fopen($line[1] . ".html", "w");
			fwrite($writeFilePointer, $result);
			fclose($writeFilePointer);
		}
		fclose($filePointer);
	}
}

if (empty($argv[1])) {
	throw new Exception("No Arguments");
}

$generator = new GenerateTestCase();
$generator->html($argv[1]);