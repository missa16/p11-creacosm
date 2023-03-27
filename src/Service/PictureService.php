<?php
namespace App\Service;

use Doctrine\DBAL\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;


    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @throws Exception
     */
    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // on donne un nouveau nom à l'image
        $fichier = md5(uniqid('image_sondage',true)). '.jpeg';

        // on recupere les infos de l'image
        $picture_info = getimagesize($picture);

        if(!$picture_info){
            throw new Exception('Format d\'image incorrect');
        }

        // on verifie le format de l'image
        switch ($picture_info['mime']){
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default :
                throw new Exception('Format d\'image incorrect');
        }

        // on recadre l'image,
        // on récupère les dimensions
        $imageWidth = $picture_info[0];
        $imageHeight = $picture_info[1];


        // on vérifie l'orientation de l'image
        switch ($imageWidth <=> $imageHeight){
            case -1: // la largeur est inferieur à la hauteur = portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize)/2 ;
                break;
            case 0: //  carre
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: // paysage
                $squareSize = $imageHeight;
                $src_y = 0;
                $src_x = ($imageWidth - $squareSize)/2 ;
                break;
        }
        // on crée une nouvelle image "vierge"
        $resize_picture = imagecreatetruecolor($width,$height);
        imagecopyresampled($resize_picture,$picture_source,0,0,$src_x,$src_y,$width,$height,$squareSize,$squareSize);

        $path = $this->params->get('images_directory').$folder;

        // on crée le dossier de destination s'il n'existe pas
        if(!file_exists($path.'/mini/')){
            mkdir($path.'/mini/',0755,true);
        }

        // On stocke l'image recadrée
        imagejpeg($resize_picture,$path.'/mini/'.$width.'x'.$height.'-'.$fichier);

        $picture->move($path.'/',$fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder='',?int $width = 250, ?int $height = 250){

        if($fichier !== 'default.webp'){
            $success = false;
            $path = $this->params->get('images_directory').$folder;
            $mini = $path . '/mini/'.$width.'x'.$height.'-'.$fichier;

            if(file_exists($mini)){
                unlink($mini);
                $success=true;
            }

            $original = $path.'/'.$fichier;
            if(file_exists($original)){
                unlink($original);
                $success=true;
            }
            return $success;
        }
        return false;
    }

}

