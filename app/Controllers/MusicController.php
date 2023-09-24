<?php

namespace App\Controllers;
use App\Models\PlaylistModel;
use App\Models\PlayListMusicModel;
use App\Models\MusicModel;

class MusicController extends BaseController
{
    public function index()
{
    $musicModel = new MusicModel();
    $musicList = $musicModel->findAll();
    $playlistModel = new PlaylistModel();
    $playlists = $playlistModel->findAll();

    return view('music_player', ['musicList' => $musicList, 'playlists' => $playlists]);
}


    public function createPlaylist()
    {
        $playlistName = $this->request->getPost('playlist_name');

        $playlistModel = new PlaylistModel();
        $playlistModel->insert(['name' => $playlistName]);

        return redirect()->to('/');
    }

    public function getPlaylists()
    {
        $playlistModel = new PlaylistModel();
        $playlists = $playlistModel->findAll();

        return $this->response->setJSON($playlists);
    }

    public function uploadMusic()
    {
        $musicModel = new MusicModel();

        $file = $this->request->getFile('musicFile'); 

        if ($file->isValid() && $file->getClientExtension() === 'mp3') {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $newName); 

          
            $musicModel->insert([
                'file_name' => $newName,
                'file_path' => 'uploads/' . $newName,
            ]);

            return redirect()->to('/')->with('success', 'Music uploaded successfully');
        } else {
            return redirect()->to('/music')->with('error', 'Invalid or unsupported file format');
        }
    }
    public function addToPlaylist()
    {
        $musicID = $this->request->getPost('musicID');
        $playlistID = $this->request->getPost('playlistID');
    
        
    
        $playlistMusicModel = new PlaylistMusicModel();
        $existingAssociation = $playlistMusicModel->where('playlist_id', $playlistID)
                                                ->where('music_track_id', $musicID)
                                                ->countAllResults();
        
        if ($existingAssociation === 0) {
            $playlistMusicModel->insert([
                'playlist_id' => $playlistID,
                'music_track_id' => $musicID,
            ]);
    
            return redirect()->to('/')->with('success', 'Music added to the playlist.');
        } else {
            return redirect()->to('/')->with('error', 'Music is already in the playlist.');
        }
        return redirect()->to('/')->with('success', 'Music added to the playlist.');
    }


    public function getPlaylistMusic()
{
    $playlistID = $this->request->getPost('playlistID');
    $musicModel = new MusicModel();
    $musicList = $musicModel->where('playlist_id', $playlistID)->findAll();

    return $this->response->setJSON($musicList);
}

    
}
