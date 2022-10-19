<?php

namespace App\Components;

use LastFmApi\Api\AlbumApi;
use LastFmApi\Api\AuthApi;
use LastFmApi\Api\ArtistApi;

class LastFM
{
    private $apiKey;
    private $artistApi;
    private $albumApi;

    public function __construct()
    {
        $this->apiKey = env('LAST_FM_API_KEY'); //required
        $auth = new AuthApi('setsession', array('apiKey' => $this->apiKey));
        $this->artistApi = new ArtistApi($auth);
        $this->albumApi = new AlbumApi($auth);
    }

    public function getArtistInfo($artist): array
    {
        return $this->artistApi->getInfo(array("artist" => $artist));
    }

    public function searchAlbum($album): array
    {
        return $this->albumApi->search(['album' => $album]);
    }

    public function getAlbumInfo($album, $artist): array
    {
        return $this->albumApi->getInfo(['album' => $album, 'artist' => $artist, 'autocorrect' => 1]);
    }
}
