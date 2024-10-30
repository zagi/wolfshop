<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Item;

class ItemRepository
{
    public function getAllItems(): Collection
    {
        return Item::all();
    }

    public function findByName(string $name): ?Item
    {
        return Item::where('name', $name)->first();
    }

    public function save(Item $item): bool
    {
        return $item->save();
    }

    public function create(array $data): Item
    {
        return Item::create($data);
    }

    public function updateOrCreate(array $attributes, array $values): Item
    {
        return Item::updateOrCreate($attributes, $values);
    }
}
