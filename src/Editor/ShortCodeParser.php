<?php
namespace Aman5537jains\AbnCms\Editor;


class ShortCodeParser{


    public static function findMatches($shortcode, $html)
    {
        // RegEx: https://www.regextester.com/104625
        $regex = '/\[' . $shortcode . '(\s.*?)?\](?:([^\[]+)?\[\/' . $shortcode . '\])?/';
        preg_match_all($regex, $html, $pregMatchAll);
        $fullMatches = $pregMatchAll[0];
        $matchAttributeStrings = $pregMatchAll[1];

        // loop through the attribute strings of each $shortcode instance and add the parsed variants to $matches
        $matches = [];
        foreach ($matchAttributeStrings as $i => $matchAttributeString) {
            $matchAttributeString = trim($matchAttributeString);
            // as long as there are attributes in the attributes string, add them to $attributes
            $attributes = [];
            while (strpos($matchAttributeString, '=') !== false) {
                list($attribute, $remainingString) = explode('=', $matchAttributeString, 2);
                $attribute = trim($attribute);

                // if first char is " and at least two " exist, get attribute value between ""
                if (strpos($remainingString, '"') === 0 && strpos($remainingString, '"', 1) !== false) {
                    list($empty, $value, $remainingString) = explode('"', $remainingString, 3);
                    $attributes[$attribute] = $value;
                } elseif (strpos($remainingString, ' ') !== false) {
                    // attribute value was not between "", get value until next whitespace or until end of $remainingString
                    list($value, $remainingString) = explode(' ', $remainingString, 2);
                    $attributes[$attribute] = $value;
                } else {
                    $attributes[$attribute] = $remainingString;
                    $remainingString = '';
                }

                $matchAttributeString = $remainingString;
            }

            $matches[] = [
                'shortcode' => $fullMatches[$i],
                'attributes' => $attributes
            ];
        }

        return $matches;
    }


}
