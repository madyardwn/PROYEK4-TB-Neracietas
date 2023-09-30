<?php

namespace App\Http\Controllers;

use App\DataTables\EventsDataTable;
use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
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
            'type' => 'required|in:proker,kegiatan,lomba,pekerjaan',
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
            'type' => [
                'required' => 'Tipe harus diisi',
                'in' => 'Tipe harus berupa proker, kegiatan, lomba, atau pekerjaan',
            ],
            'location' => [
                'required' => 'Lokasi harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        try {
            if ($request->hasFile('poster')) {
                $currentDate = date('Y-m-d-H-i-s');
                // $filename = $currentDate . '_' . $request->name . '.' . $request->poster->extension();
                // replace space with underscore
                $filename = $currentDate . '_' . str_replace(' ', '_', $request->name) . '.' . $request->poster->extension();
                $poster = $request->poster->storeAs('cabinets/events/poster', $filename, 'public');
            }

            Event::create([
                'name' => $request->name,
                'description' => $request->description,
                'poster' => $poster,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'type' => $request->type,
                'is_active' => $request->date >= Carbon::now()->format('Y-m-d') ? true : false,
            ]);

            return response()->json([
                'message' => 'Event ' . $request->name . ' berhasil ditambahkan',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Event ' . $request->name . ' gagal ditambahkan',
            ], 500);
        }
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
            'type' => 'required|in:proker,kegiatan,lomba,pekerjaan',
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
            'type' => [
                'required' => 'Tipe harus diisi',
                'in' => 'Tipe harus berupa proker, kegiatan, lomba, atau pekerjaan',
            ],
        ];

        $request->validate($rules, $message);

        try {
            $event = Event::find($id);

            if ($request->hasFile('poster')) {
                // delete old image
                if ($event->poster) {
                    Storage::disk('public')->delete($event->poster);
                }

                $currentDate = date('Y-m-d-H-i-s');
                // $filename = $currentDate . '_' . $request->name . '.' . $request->poster->extension();
                // replace space with underscore
                $filename = $currentDate . '_' . str_replace(' ', '_', $request->name) . '.' . $request->poster->extension();
                $poster = $request->poster->storeAs('cabinets/events/poster', $filename, 'public');
            }

            $event->update([
                'name' => $request->name,
                'description' => $request->description,
                'poster' => $poster ?? $event->poster,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'type' => $request->type,
                'is_active' => $request->date >= Carbon::now()->format('Y-m-d') ? true : false,
            ]);

            return response()->json([
                'message' => 'Event ' . $event->name . ' berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah event',
            ], 500);
        }
    }

    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        $count = 0;

        try {
            foreach ($ids as $id) {
                $event = Event::find($id);

                if ($event->poster) {
                    Storage::disk('public')->delete($event->poster);
                }

                $event->delete();
                $count++;
            }

            if ($count > 0) {
                $message = 'Berhasil menghapus ' . $count . ' event';

                return response()->json([
                    'message' => $message,
                ], 200);
            }

            return response()->json([
                'message' => 'Tidak ada event yang berhasil dihapus',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus event',
            ], 500);
        }
    }

    public function notification(Event $event, Request $request)
    {
        if (!$event->is_active) {
            return response()->json([
                'message' => 'Event ' . $event->name . ' sudah berakhir',
            ], 403);
        }

        $url = env('FCM_URL');

        $serverKey = env('FCM_SERVER_KEY');

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $notification = [
            'title' => $request->title ?? 'Event ' . $event->name,
            'body' => $request->body ?? 'Event ' . $event->name . ' akan dilaksanakan pada ' . $event->date . ' pukul ' . $event->time . ' di ' . $event->location,
            'link' => $request->link ?? '',
            'poster' => $event->poster ? asset('storage/' . $event->poster) : '',
        ];

        $data = [
            'event_id' => $event->id,
        ];

        // $fcmTokens = User::whereNotNull('device_token')->pluck('device_token')->all();
        $fcmTokens = User::query()
            ->select([
                'users.device_token',
                'periodes.is_active'
            ])
            ->leftJoin('periodes', 'periodes.user_id', '=', 'users.id')
            ->where('periodes.is_active', true)
            ->whereNotNull('users.device_token')
            ->pluck('users.device_token')
            ->all();

        $chunks = array_chunk($fcmTokens, 50);        

        Notification::create([
            'title' => $notification['title'],
            'body' => $notification['body'],
            'link' => $notification['link'],
            'poster' => $notification['poster'],
        ]);

        foreach ($fcmTokens as $token) {
            $user = User::where('device_token', $token)->first();
            $user->notifications()->attach(Notification::latest()->first()->id, [
                'created_at' => Carbon::now(), 
                'updated_at' => Carbon::now()
            ]);
        }

        foreach ($chunks as $chunk) {
            $fields = [
                'registration_ids' => $chunk,
                'notification' => $notification,
                'data' => $data,
            ];

            $payload = json_encode($fields);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);
            curl_close($curl);            
        }

        return response()->json([
            'message' => 'Notifikasi berhasil dikirim',
        ], 200);                
    }
}
