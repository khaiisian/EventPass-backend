<?php

namespace App\Services;

use App\Models\Event;
use App\Models\TicketType;
use App\Traits\CodeGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class EventService
{
    use CodeGenerator;
    public function connection()
    {
        return new Event;
    }

    public function getAll($perPage = 10)
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->with(['eventType', 'venue', 'organizer'])
            ->paginate($perPage);
    }

    public function getById($id)
    {
        return $this->connection()
            ->where('EventId', $id)
            ->where('DeleteFlag', false)
            ->with(['eventType', 'venue', 'organizer', 'ticketTypes'])
            ->firstOrFail();
    }

    public function getTopEvents()
    {
        return Event::with('eventType', 'venue', 'organizer')
            ->orderByDesc('SoldOutTicketQuantity')
            ->where('DeleteFlag', false)
            ->where('EventStatus', 2)
            ->take(value: 4)
            ->get();
    }

    public function create(array $data)
    {
        try {
            $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
            $data['CreatedAt'] = now();
            $data['EventCode'] = $this->generateCode('EV', 'EventId', 'EventCode', Event::class);

            if (!empty($data['EventImage'])) {
                $image = $data['EventImage'];
                $imageName = time() . '_' . $image->getClientOriginalName();
                $data['EventImage'] = $image->storeAs('images', $imageName, 'public');
            }

            $ticketTypes = $data['TicketTypes'];
            unset($data['TicketTypes']);
            $event = $this->connection()->create($data);

            foreach ($ticketTypes as $ticket) {
                $ticketTypeCode = $this->generateCode('TT', 'TicketTypeId', 'TicketTypeCode', TicketType::class);

                TicketType::create([
                    'EventId' => $event->EventId,
                    'TicketTypeCode' => $ticketTypeCode,
                    'TicketTypeName' => $ticket['TicketTypeName'],
                    'Price' => $ticket['Price'],
                    'TotalQuantity' => $ticket['TotalQuantity'],
                    'CreatedAt' => now(),
                    'CreatedBy' => 'admin',
                ]);
            }
            DB::commit();
            return $event;

        } catch (\Exception $e) {
            \Log::error('Event creation failed: ' . $e->getMessage());
            throw $e;
        }
    }


    public function update(array $data, $id)
    {
        DB::beginTransaction();

        try {
            $data['ModifiedAt'] = now();
            $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';

            $ticketTypes = $data['TicketTypes'] ?? [];
            unset($data['TicketTypes']);

            $event = $this->connection()
                ->where('DeleteFlag', false)
                ->findOrFail($id);

            if (!empty($data['EventImage'])) {
                if ($event->EventImage) {
                    Storage::disk('public')->delete($event->EventImage);
                }

                $image = $data['EventImage'];
                $imageName = time() . '_' . $image->getClientOriginalName();
                $data['EventImage'] = $image->storeAs('images', $imageName, 'public');
            }

            $event->update($data);

            foreach ($ticketTypes as $ticket) {

                if (!empty($ticket['TicketTypeCode'])) {

                    TicketType::where('TicketTypeCode', $ticket['TicketTypeCode'])
                        ->where('EventId', $event->EventId)
                        ->update([
                            'TicketTypeName' => $ticket['TicketTypeName'],
                            'Price' => $ticket['Price'],
                            'TotalQuantity' => $ticket['TotalQuantity'],
                            'ModifiedAt' => now(),
                            'ModifiedBy' => auth()->user()?->UserCode ?? 'admin',
                        ]);

                } else {

                    TicketType::create([
                        'EventId' => $event->EventId,
                        'TicketTypeCode' => $this->generateCode(
                            'TT',
                            'TicketTypeId',
                            'TicketTypeCode',
                            TicketType::class
                        ),
                        'TicketTypeName' => $ticket['TicketTypeName'],
                        'Price' => $ticket['Price'],
                        'TotalQuantity' => $ticket['TotalQuantity'],
                        'CreatedAt' => now(),
                        'CreatedBy' => auth()->user()?->UserCode ?? 'admin',
                    ]);
                }
            }

            DB::commit();
            return $event;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy($id)
    {
        $event = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $event->DeleteFlag = true;
        $event->ModifiedAt = now();
        $event->ModifiedBy = "system";

        return $event->save();
    }

    public function search(array $params)
    {
        $query = Event::query()
            ->where('DeleteFlag', false)
            ->where('IsActive', true)
            ->with(['eventType', 'venue', 'organizer']);

        if (!empty($params['event_type_id'])) {
            $query->where('EventTypeId', $params['event_type_id']);
        }

        if (!empty($params['search'])) {
            $query->where('EventName', 'LIKE', '%' . $params['search'] . '%');
        }

        switch ($params['sort_by'] ?? null) {
            case 'name_asc':
                $query->orderBy('EventName', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('EventName', 'desc');
                break;

            case 'date_asc':
                $query->orderBy('StartDate', 'asc');
                break;

            case 'date_desc':
                $query->orderBy('StartDate', 'desc');
                break;

            case 'popular':
                $query->orderBy('SoldOutTicketQuantity', 'desc');
                break;

            default:
                $query->orderBy('CreatedAt', 'desc');
        }

        return $query->paginate($params['per_page'] ?? 10);
    }
}