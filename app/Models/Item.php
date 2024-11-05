<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\DTO\ItemDTO;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quality', 'sellIn', 'imgUrl'];

    public function __toString(): string
    {
        return "{$this->name}, {$this->sellIn}, {$this->quality}";
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    public function toDTO(): ItemDTO
    {
        $itemDTO = new ItemDTO(
            $this->name,
            $this->sellIn,
            $this->quality,
        );

        $itemDTO->setImgUrl($this->imgUrl ?? '');

        return $itemDTO;
    }
}
