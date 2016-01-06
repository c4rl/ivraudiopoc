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
   * @todo Use audible words for numbers instead of numeric characters.
   *
   * @param string $currency
   *   Literal numeric currency value, e.g. '-$123.45'
   *
   * @return string
   *   Audible string representing currency, e.g. "minus 123 dollars and 45 cents.
   */
  static public function parseCurrency($currency) {

    // Remove non-numeric, minus sign, decimal.
    $value = (float) preg_replace('/[^0-9\-\.]/', '', $currency);

    // Parse out sign.
    if ($value < 0) {
      $parts[] = 'minus';
    }

    $abs_value = abs($value);

    // Parse out dollars and cents.
    $dollars = intval($abs_value);
    $parts[] = $dollars;
    $parts[] = 'dollars and';

    $cents = intval(intval($abs_value - $dollars) * 100);
    $parts[] = $cents;
    $parts[] = 'cents';

    return implode(' ', $parts);
  }

}
