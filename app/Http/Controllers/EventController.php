<?php

namespace App\Http\Controllers;

use App\DataTables\EventsDataTable;
use App\Models\Cabinet;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(EventsDataTable $dataTable)
    {
        return $dataTable->render('pages.events.index', [
            'cabinets' => Cabinet::all(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:50',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'cabinet' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
            ],
            'image' => [
                'required' => 'Gambar harus diisi',
                'image' => 'File harus berupa gambar',
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
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
            ]
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('image')) {
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('image')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            $cabinet = Cabinet::find($request->cabinet);
            $image = $request->file('image')->storeAs($cabinet->year . '-' . $cabinet->name . '/' . 'events' . '/' . $request->name . '/poster', $filename, 'public');
        }

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'cabinet_id' => $request->cabinet,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan');
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
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'cabinet' => 'required',
        ];

        $message = [
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'description' => [
                'required' => 'Deskripsi harus diisi',
            ],
            'image' => [
                'image' => 'File harus berupa gambar',
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
        if ($request->hasFile('image')) {
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('image')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            // delete old logo
            if ($event->image) {
                unlink(public_path('storage/' . $event->image));
            }

            $cabinet = Cabinet::find($request->cabinet);

            $image = $request->file('image')->storeAs($cabinet->year . '-' . $cabinet->name . '/' . 'events' . '/' . $request->name . '/poster', $filename, 'public');
        }

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'cabinet_id' => $request->cabinet,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil diubah');
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        // delete image
        if ($event->image) {
            unlink(public_path('storage/' . $event->image));
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus');
    }
}
