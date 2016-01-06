<?php
/**
 * @file
 * AudibleString class.
 */

/**
 * Class AudibleString.
 */
class AudibleString {

  /**
   * Return a currency string in literal text.
   *
   * @param string $currency
   *   Literal numeric currency value, e.g. '-$123.45'
   *
   * @return string
   *   Audible string representing currency, e.g. "minus one hundred twenty three dollars and forty five cents.
   */
  static public function currencyToWords($currency) {

    // Remove non-numeric, minus sign, decimal.
    $value = (float) preg_replace('/[^0-9\-\.]/', '', $currency);

    // Parse out sign.
    if ($value < 0) {
      $parts[] = 'minus';
    }

    $abs_value = abs($value);

    // Parse out dollars and cents.
    $dollars = intval($abs_value);
    $parts[] = self::integerToWords($dollars);
    $parts[] = 'dollars and';

    $cents = intval(($abs_value - $dollars) * 100);
    $parts[] = self::integerToWords($cents);
    $parts[] = 'cents';

    return implode(' ', $parts);
  }

  /**
   * Convert integer to words.
   *
   * @param int $integer
   *   Positive integer less than 100000
   *
   * @return string
   *   String of english words describing number.
   */
  static public function integerToWords($integer) {

    $number_dictionary = self::numberDictionary();

    // Break into chunks for 100000-1000 places vs 100-1 places.
    // Example: 12345 becomes [12, 345].
    $three_digit_chunks = array_reverse(array_map(function ($reversed_chunk) {
      return (int) sprintf('%d', strrev($reversed_chunk));
    }, str_split(strrev((string) $integer), 3)));

    // For each chunk, divide into words.
    $words_chunks = array_map(function ($digit_chunk) use ($number_dictionary) {
      $words = [];

      // Get hundreds place, suffix with "hundred"
      $hundreds_place = intval($digit_chunk / 100);
      if ($hundreds_place > 0) {
        $words[] = $number_dictionary[$hundreds_place];
        $words[] = 'hundred';
        $remainder_less_hundreds = $digit_chunk - $hundreds_place * 100;
      }
      else {
        $remainder_less_hundreds = $digit_chunk;
      }

      // Get tens place, accounting for teen numbers.
      $tens_place = intval($remainder_less_hundreds / 10);
      if ($tens_place > 1) {
        $words[] = $number_dictionary[$tens_place * 10];
        $remainder_less_tens = $remainder_less_hundreds - $tens_place * 10;
      }
      elseif ($tens_place > 0) {
        $words[] = $number_dictionary[$remainder_less_hundreds];
        $remainder_less_tens = 0;
      }
      else {
        $remainder_less_tens = $remainder_less_hundreds;
      }

      // Get ones place.
      if ($remainder_less_tens > 0) {
        $words[] = $number_dictionary[$remainder_less_tens];
      }

      return implode(' ', $words);

    }, $three_digit_chunks);

    if (count($words_chunks) > 1) {
      return trim(sprintf('%s thousand %s', $words_chunks[0], $words_chunks[1]));
    }
    else {
      return $words_chunks[0];
    }
  }


  /**
   * Dictionary of number words.
   *
   * @return string[]
   *   Dictionary of number words.
   */
  static private function numberDictionary() {
    return [
      1 => 'one',
      2 => 'two',
      3 => 'three',
      4 => 'four',
      5 => 'five',
      6 => 'six',
      7 => 'seven',
      8 => 'eight',
      9 => 'nine',
      10 => 'ten',
      11 => 'eleven',
      12 => 'twelve',
      13 => 'thirteen',
      14 => 'fourteen',
      15 => 'fifteen',
      16 => 'sixteen',
      17 => 'seventeen',
      18 => 'eighteen',
      19 => 'nineteen',
      20 => 'twenty',
      30 => 'thirty',
      40 => 'forty',
      50 => 'fifty',
      60 => 'sixty',
      70 => 'seventy',
      80 => 'eighty',
      90 => 'ninety',
      100 => 'hundred',
      1000 => 'thousand',
    ];
  }

}
