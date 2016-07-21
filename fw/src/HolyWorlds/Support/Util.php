<?php
namespace HolyWorlds\Support;

use Calendar;
use Penoaks\Http\UploadedFile;
use Penoaks\Support\Collection;

class Util
{
	/**
	* Create a Calendar with the given collection of events.
	*
	* @param  Collection  $events
	* @return array
	*/
	public static function createCalendarFromEvents(Collection $events)
	{
		$calendarEvents = [];
		foreach ($events as $event) {
			$calendarEvents[] = Calendar::event(
				$event->title,
				$event->all_day,
				$event->starts,
				$event->ends,
				$event->id,
				[
				'url' => $event->url,
				'color' => '#f44336'
				]
			);
		}

		return Calendar::addEvents($calendarEvents);
	}

	public static function prepareExpression( $perm )
	{
		if ( Util::startsWith( $perm, '$' ) )
			return substr( $perm, 1 );

		$perm = str_replace( '.', '\.', $perm );
		$perm = str_replace( '*', '(.*)', $perm );

		if ( preg_match( '/(\d+)-(\d+)/', $perm, $matches, PREG_OFFSET_CAPTURE ) )
			foreach ( $matches as $match )
				$perm = str_replace( $match[0], '(' . implode( '|', range( $match[1], $match[2] ) ) . ')' );

		return '/' . $perm . '/';
	}

	public static function format_phone($phone)
	{
		$phone = preg_replace("/[^0-9]/", "", $phone);

		if(strlen($phone) == 7)
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
		elseif(strlen($phone) == 10)
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
		else
			return $phone;
	}

	public static function filter($Arr, $AllowedKeys)
	{
		return array_intersect_key($Arr, array_flip($AllowedKeys));
	}

	public static function rand( $length, $numbers = true, $letters = true, $allowedChars = array() )
	{
		if ( $allowedChars == null )
			$allowedChars = array();

		if ( $numbers )
			$allowedChars = array_merge( $allowedChars, array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" ) );

		if ( $letters )
			$allowedChars = array_merge( $allowedChars, array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" ) );

		$rtn = "";
		for ( $i = 0; $i < $length; $i++ )
			$rtn .= $allowedChars[ rand( 0, count( $allowedChars ) - 1 ) ];

		return $rtn;
	}

	public static function create_table($tableArray, $headerArray = "", $tableID = "")
	{
		$x = 0;
		echo("<table id=\"" . $tableID . "\" class=\"altrowstable\">");

		if (is_array($headerArray) && count($headerArray) > 0)
		{
			echo("<tr>");
			foreach($headerArray as $col)
			{
				echo("<th>" . $col . "</th>");
			}
			echo("</tr>");
		}

		foreach($tableArray as $row)
		{
			$class = ($x % 2 == 0) ? "evenrowcolor" : "oddrowcolor";
			echo("<tr id=\"" . $row["rowId"] . "\" rel=\"" . $row["metaData"] . "\" class=\"" . $class . "\">");

			$row["metaData"] = null;
			$row["rowId"] = null;

			if (is_array($row))
			{
				$cc = 0;
				foreach($row as $col)
				{
					if ( !is_null( $col ) )
					{
						$subclass = (empty($col)) ? " emptyCol" : "";

						echo("<td id=\"col_" . $cc . "\" class=\"" . $subclass . "\">" . $col . "</td>");
						$cc++;
					}
				}
			}
			else
			{
				echo( "<td style=\"text-align: center; font-weight: bold;\" class=\"" . $class . "\" colspan=\"" . count( $headerArray ) . "\">" . $row . "</td>" );
			}
			echo( "</tr>" );
			$x++;
		}
		echo("</table>");
	}

	public static function startsWith($haystack, $needle)
	{
		return substr( $haystack, 0, strlen($needle) ) === $needle;
	}

	public static function endsWith($haystack, $needle)
	{
		return substr( $haystack, -strlen( $needle ) ) === $needle;
	}

	/**
	 * Convert the given string to a URL-friendly slug.
	 *
	 * @param  string  $string
	 * @return string
	 */
	public static function slugify($string)
	{
		return str_slug( str_replace( '&amp;', 'and', $string ), '-' );
	}

	public static function stackTrace()
	{
		$stack = debug_backtrace();
		$output = '';

		$stackLen = count($stack);
		for ($i = 1; $i < $stackLen; $i++)
		{
			$entry = $stack[$i];

			$func = $entry['function'] . '(';
			$argsLen = count($entry['args']);
			for ($j = 0; $j < $argsLen; $j++)
			{
				$my_entry = $entry['args'][$j];
				if (is_string($my_entry))
				{
					$func .= $my_entry;
				}
				if ($j < $argsLen - 1) $func .= ', ';
			}
			$func .= ')';

			$entry_file = 'NO_FILE';
			if (array_key_exists('file', $entry))
			{
				$entry_file = $entry['file'];
			}
			$entry_line = 'NO_LINE';
			if (array_key_exists('line', $entry))
			{
				$entry_line = $entry['line'];
			}
			$output .= $entry_file . ':' . $entry_line . ' - ' . $func . PHP_EOL;
		}
		return $output;
	}
}
