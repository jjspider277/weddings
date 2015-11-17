<?php

namespace backend\ComunBundle\Util;

use backend\CatarinoBundle\Entity\Palabra;
use backend\CatarinoBundle\Entity\PalabraLey;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author Julio Jesus <julio.garcia@adepsoft.com>
 */
class DiccionarioIndexacion
{
    public static function excludedWordsForSearch()
    {

        //ordenado
        return array('a','acaso','además','ahora','ahí','al','algo','allende','allí','alrededor','ante','anteayer','antes','aparte','aquí','asimismo','así',
            'ayer','aún','bajo','bastante','bien','cabe','casi','cerca','con','como','como','con','contra','cuando','de','debajo','debe','del','delante','demasiado','dentro',
            'deprisa','desde','despacio','después','detrás','donde','durante','el','en','encima','entre','excepto','fuera','hacia','hasta','hoy','incluso',
            'incluso','jamás','la','las','lejos','lo','los','luego','mal','mediante','medio','mejor','menos','menos','mucho','muy','más','nada','no','nunca',
            'o','otro','otra','para','poco','por','pro','pronto','que','quizá','quizás','salvo','se','según','si','sí','sin','so','sobre','su','tal','también','tampoco','tan','tanto','tarde','temprano','todavía',
            'todo','tras','un','una','uno','versus','vez','vía','y','ya');

    }

    public static function pivotsForSearch()
    {
        $indice=array();

       $array = DiccionarioIndexacion::excludedWordsForSearch();
        // ld(sort($array));
        $flag=ord(lcfirst($array[0]{0}));
        //ld($flag);
        foreach($array as $pos=>$value){
            //     echo utf8_decode($value."','");
            if (ord(lcfirst($value{0}))!=$flag)
            {
                $indice[$flag]=$pos;
                $flag = ord(lcfirst($value{0}));
            }

        }
        $indice[$flag]=count($array);
        return $indice;


    }

    public static function normalizeString($string)
    {
        $string=strtolower($string);
        $a=str_replace(['á','é','í','ó','ú','ñ',','],['a','e','i','o','u','n',''],$string);
        return $a;

    }
    public static function autoIndexSearch()
    {
        $em= UtilRepository2::getEntityManager();
        $leyes = UtilRepository2::getRepo("CatarinoBundle:Ley")->findAll();
$i=1;
        foreach ($leyes as $ley)
        {
           $array= DiccionarioIndexacion::excludefromSearch($ley->__toString());
            foreach ($array as $arr)
            {
         $palabra = UtilRepository2::getRepo("CatarinoBundle:Palabra")->findOneBy(array('valor_norm'=>DiccionarioIndexacion::normalizeString($arr)));

                if ($palabra!=null)
                {

                    $palabraLey = UtilRepository2::getRepo("CatarinoBundle:PalabraLey")->findBy(array("palabra"=>$palabra->getId(),"ley"=>$ley->getId()));
                     if ($palabraLey!=null)
                     {
                         $palabraLey = $palabraLey[0];
                         $palabraLey->setFrecuencia($palabraLey->getFrecuencia()+1);
                         $em->persist($palabraLey);
                         $em->flush();
                     }else
                     {
                         $palabraLey = new PalabraLey();
                         $palabraLey->setValor(" ");
                         $palabraLey->setPeso("0.15");

                         $palabraLey->setFrecuencia("1");
                         $palabraLey->setPalabra($palabra);
                         $palabraLey->setLey($ley);
                         $em->persist($palabraLey);
                         $em->flush();
                     }
                }else{
                    $palabra = new Palabra();
                    $palabra->setClave("0.15");
                    $palabra->setValor($arr);
                    $palabra->setValorNorm(DiccionarioIndexacion::normalizeString($arr));
                    $em->persist($palabra);
                    $palabraLey = new PalabraLey();
                    $palabraLey->setValor(" ");
                    $palabraLey->setPeso("0.15");

                    $palabraLey->setFrecuencia("1");
                    $palabraLey->setPalabra($palabra);
                    $palabraLey->setLey($ley);
                    $em->persist($palabraLey);
                    $em->flush();

                }
            }
        }


        return;
    }
    public static function excludefromSearch($string)
    {
        //hacer un arreglo que tenga los indices alfabeticos actualizados para hacer busquedas NLogN
        $exploded= explode(" ",$string);

        $indice= DiccionarioIndexacion::pivotsForSearch();
     //   ldd($indice);
        $words=DiccionarioIndexacion::excludedWordsForSearch();
         foreach ($exploded as $pos=>$value)
         {

             $value=lcfirst($value);
             if(strlen($value)==0)
                 continue;
             $exploded[$pos]=DiccionarioIndexacion::normalizeString($value);
             $val = $value[0];
             if (is_numeric($val)) {
            //     ld($value[0]);
                 continue;
             }
             $numericValue = ord($value{0});
             $iterator= $numericValue;
             if (!isset($indice[$iterator]))
             continue;
             $end=$indice[$iterator];
             if ($iterator=="97")
                 $start=0;
             else
             {
                 while (!isset($indice[$iterator-1]))
                     $iterator--;
                 $start=$indice[$iterator-1];
             }

            //             $letter = chr(98);
           for ($i=$start;$i<$end;$i++)
           {

            if ($value == $words[$i])
            {
                unset($exploded[$pos]);
            }
           }
         }
       return $exploded;

    }

    public static function findResumen($string)
    {
        $data=array("A","Al","Cualquier","Cuando","Dentro","Durante","De","El","En","Estará","Este","Interpuesta","Las","La","Los","Ninguna","Ningún","Podrá",
                    "Promovida","Sólo","Solo","Si","Siempre","Se","Ser","Será","Según","Segun","Son","Tratáandose","Toda","Todas","Para","Previo","Quienes","Una");

        $maxleng = strlen($string)-1;
        $palabraDiccionario = false;
          $inicio=-1;
        $posInicio=-1;
        $posFinal=-1;
        for($i=0;$i < $maxleng ;$i++){
            if($string[$i] == strtoupper($string[$i]) && ($string[$i] != ' ')  && ($posInicio != 1))
            {
                $posInicio++;
            if($posInicio==1)
                $inicio=$i;

            }
            elseif (($string[$i] == ' ') && ($inicio!=-1) )
            {
               $posFinal = $i-$inicio;
                if (in_array(substr($string,$inicio,$posFinal),$data))
                       return $inicio-1;
                else
                    $posInicio--;
                $inicio=-1;



            }
        }
        return -1;

    }
}
