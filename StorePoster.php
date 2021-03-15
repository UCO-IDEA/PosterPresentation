<?php

require_once '../../apis/google/google-api-php-client-2.4.0/vendor/autoload.php';

require_once 'config.php';

require_once 'DataAccess/PosterDataAccess.php';

try {

if(isset($annotationArray)){
  $annotationArray = $_POST[ 'annotations' ];
}else{
    $annotationArray = "";
}
    
    
  $styleArray = $_POST[ 'annotationStyle' ];

  $domArray = $_POST[ 'annotationDOM' ];


  if ( !empty( $annotationArray ) ) {
    for ( $i = 1; $i <= count( $annotationArray ); $i++ ) {
      $ann = $annotationArray[ $i ];

      $res = preg_match_all( "%(?:(?:https?|ftp)://)((?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+))(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu",
        $ann[ 'content' ],
        $matches,
        PREG_PATTERN_ORDER );

      if ( $res != false ) {
        if ( array_search( $matches[ 1 ][ 0 ], $config[ "allowedDomains" ] ) === false ) {
          $ann[ 'content' ] = str_replace( $matches[ 0 ][ 0 ], "\"", $ann[ 'content' ] );

          $annotationArray[ $i ] = $ann;
        }
      }
    }
  }

  $annotations = json_encode( $annotationArray );

  $poster = putPosterData( $annotations, $styleArray, $domArray, $_POST[ 'posterURL' ] );

  echo $poster;

} catch ( Exception $e ) {
  echo "-1";
  echo $e;
}
?>
