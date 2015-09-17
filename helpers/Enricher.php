<?php
namespace app\helpers;

use Yii;
use yii\helpers\Html;

mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

class Enricher {
    private static $place_names = [
        'Hellas' => 'http://pleiades.stoa.org/places/1001896',
        'Boeotia' => 'http://pleiades.stoa.org/places/540689',
        'Peloponnese' => 'http://pleiades.stoa.org/places/570577',
        'Arcadia' => 'http://pleiades.stoa.org/places/570102',
        'Attica' => 'http://pleiades.stoa.org/places/579888',
        'Ionia' => 'http://pleiades.stoa.org/places/550597',
        'Phthiotis' => 'http://pleiades.stoa.org/places/541052',
        'Danaans' => 'http://pleiades.stoa.org/places/1001896',
        'Argives' => 'http://pleiades.stoa.org/places/1001896',
        'Achaeans' => 'http://pleiades.stoa.org/places/1001896',
        'Helleni communities' => 'http://pleiades.stoa.org/places/1001896',
        'Cyclades' => 'http://pleiades.stoa.org/places/560353',
        'Athen' => 'http://pleiades.stoa.org/places/579885',
        'Athens' => 'http://pleiades.stoa.org/places/579885',
        'Griechenland' => 'http://pleiades.stoa.org/places/1001896',
        'Böotien' => 'http://pleiades.stoa.org/places/540689',
        'Peloponnes' => 'http://pleiades.stoa.org/places/570577',
        'Arkadien' => 'http://pleiades.stoa.org/places/570102',
        'Attika' => 'http://pleiades.stoa.org/places/579888',
        'Ionien' => 'http://pleiades.stoa.org/places/550597',
        'Phthiotis' => 'http://pleiades.stoa.org/places/541052',
        'Kykladen' => 'http://pleiades.stoa.org/places/560353',
        'Karer' => 'http://pleiades.stoa.org/places/991381',
        'Griechen' => 'http://pleiades.stoa.org/places/1001896',
        'ozolischen Lokrern' => 'http://pleiades.stoa.org/places/540919',
        'Ätoliern' => 'http://pleiades.stoa.org/places/540591',
        'Akarnaniern' => 'http://pleiades.stoa.org/places/530767',
        'Griechenlands' => 'http://pleiades.stoa.org/places/1001896',
        'Lakedämoniern' => 'http://pleiades.stoa.org/places/570406',
        'Delos' => 'http://pleiades.stoa.org/places/599587',
        'Karern' => 'http://pleiades.stoa.org/places/991381',
        'Troja' => 'http://pleiades.stoa.org/places/550595',
        'Asien' => 'http://pleiades.stoa.org/places/981509',
        'Mykene' => 'http://pleiades.stoa.org/places/570491',
        'Mykener' => 'http://pleiades.stoa.org/places/570491',
        'Arkadier' => 'http://pleiades.stoa.org/places/570102',
        'Argos' => 'http://pleiades.stoa.org/places/570106',
        'Lakedämonier' => 'http://pleiades.stoa.org/places/570406',
        'Böotier' => 'http://pleiades.stoa.org/places/540689',
    ];

    public static function process($data) { 
        foreach (Enricher::$place_names as $place_name => $url) {
            $pattern = "([^a-zA-Z])({$place_name})([^a-zA-Z])";
            
            $kml_url = $url . '.kml';

            $pleiades_link = Html::a(
                \Yii::t('app', 'Show {place_name} in Pleiades', [
                    'place_name' => $place_name
                ]),
                $url,
                ['target' => '_blank']
            );

            $link = Html::a('\\2', $url, [
                'target' => '_blank',
                'rel' => 'popover',
                'data-toggle' => 'popover',
                'data-html' => 'true',
                'data-placement' => 'bottom',
                'onclick' => 'return false;',
                'data-content' => '
                <div>
                    <div class="popover-map" data-kml="' . $kml_url . '"></div>
                    <div class="padding-top-10">
                        <p>' . $pleiades_link . '</p>
                    </div>
                </div>',
            ]);

            $replacement = "\\1{$link}\\3";
            $new_data = mb_ereg_replace($pattern, $replacement, $data);

            if (false !== $new_data) {
                $data = $new_data;
            }
        }

        return $data;
    }

    public static function process_xml($data) {
        libxml_use_internal_errors(TRUE);
        $doc = new \DOMDocument();
        $doc->substituteEntities = FALSE;
        $doc->recover = TRUE;
        $doc->strictErrorChecking = FALSE;

        $data = utf8_encode(html_entity_decode($data));

        $doc->loadHTML($data);

        foreach (Enricher::$place_names as $place_name => $url) {
            $replacement = "<placeName ref=\"{$url}\">{$place_name}</placeName>";
            $pattern = "([^a-zA-Z])({$place_name})([^a-zA-Z])";

            // self::replace_dom($pattern, $replacement, $doc->documentElement, ['placeName']);
        }

        $dom = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());

        return $dom;
    }

    private static function replace_dom($regex, $replacement, $dom, $exclude = []) {
        if (!empty($dom->childNodes)) {

            foreach ($dom->childNodes as $node) {                
                if ($node->nodeType === XML_TEXT_NODE && 
                    !in_array($node->parentNode->nodeName, $exclude)) 
                {
                    $new_value = mb_ereg_replace($regex, $replacement, $node->nodeValue);

                    $fragment = $node->ownerDocument->createDocumentFragment();
                    $fragment->appendXML($new_value);
                    
                    // $node->nodeValue = $new_value;
                    $node->appendChild($fragment);

                } else {
                    self::replace_dom($regex, $replacement, $node, $exclude);
                }

            }
        }
    }
}