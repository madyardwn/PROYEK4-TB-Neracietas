<?php

namespace App\Http\Controllers;

use App\DataTables\EventsDataTable;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class EventController extends Controller
{
    public function index(EventsDataTable $dataTable)
    {
        return $dataTable->render('pages.events.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
            ],
            'poster' => [
                'required' => 'Poster harus diisi',
                'image' => 'Poster harus berupa gambar',
                'mimes' => 'Poster harus berupa gambar dengan format jpeg, png, jpg, gif, atau svg',
                'max' => 'Poster maksimal 2 MB',
            ],
            'date' => [
                'required' => 'Tanggal harus diisi',
            ],
            'time' => [
                'required' => 'Waktu harus diisi',
            ],
            'location' => [
                'required' => 'Lokasi harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('poster')) {
            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->poster->extension();
            $poster = $request->poster->storeAs('cabinets/events/poster', $filename, 'public');
        }

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'poster' => $poster,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
        ]);

        return response()->json([
            'message' => 'Event ' . $request->name . ' berhasil ditambahkan',
        ], 200);
    }

    public function edit(Event $event): Event
    {
        return $event;
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'poster' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
            ],
            'poster' => [
                'image' => 'Poster harus berupa gambar',
                'mimes' => 'Poster harus berupa gambar dengan format jpeg, png, jpg, gif, atau svg',
                'max' => 'Poster maksimal 2 MB',
            ],
            'date' => [
                'required' => 'Tanggal harus diisi',
            ],
            'time' => [
                'required' => 'Waktu harus diisi',
            ],
            'location' => [
                'required' => 'Lokasi harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        $event = Event::find($id);

        if ($request->hasFile('poster')) {
            // delete old image
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }

            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->poster->extension();
            $poster = $request->poster->storeAs('cabinets/events/poster', $filename, 'public');
        }

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'poster' => $poster ?? $event->poster,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
        ]);

        return response()->json([
            'message' => 'Event ' . $event->name . ' berhasil diubah',
        ], 200);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event ' . $event->name . ' berhasil dihapus',
        ], 200);
    }
}
