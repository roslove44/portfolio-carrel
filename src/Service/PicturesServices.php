<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PicturesServices
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // Renommer l'image
        $file = md5(uniqid(rand(), true)) . '.webp';

        // Récupérer les infos de l'image 
        $picture_infos = getimagesize($picture);
        if ($picture_infos === false) {
            throw new Exception("Format d'image incorrect");
        }

        // Vérifiez le format de l'image 
        switch ($picture_infos['mime']) {
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception("Format d'image incorrect");
                break;
        }

        // Recadrez l'image 
        // ---> Obtenir les infos Width & Height
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // -----> Vérifie l'orientation
        switch ($imageWidth <=> $imageHeight) {
            case -1: //portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0: //carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: //paysage
                $squareSize = $imageHeight;
                $src_y = 0;
                $src_x = ($imageWidth - $squareSize) / 2;
                break;
            default:
                # code...
                break;
        }

        // -----> On crée une nouvelle image
        $resized_picture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory') . $folder;

        // On crée le dossier de destination s'il n'existe pas
        // if (!file_exists($path . '/mini/')) {
        //     mkdir($path . '/mini/', 0755, true);
        // }

        // Stock l'image recadré
        // imagewebp($resized_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $file);
        $picture->move($path . '/', $file);
        return $file;
    }

    public function delete(string $file, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($file !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;
            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $file;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $file;
            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }

            return $success;
        }
        return false;
    }
}
