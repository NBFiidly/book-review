<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    public function reviews() // Untuk relasi dengan model Review
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder // untuk filter berdasarkan judul buku
    {
        return $query->where('title', 'like', '%' . $title . '%');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder | QueryBuilder // untuk flter berdasarkan jumlah review
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder | QueryBuilder // untuk filter berdasarkan rating tertinggi
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder | QueryBuilder // untuk filter berdasarkan jumlah minimum review
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    public function dateRangeFilter(Builder $query, $from = null, $to = null): Builder | QueryBuilder // untuk filter berdasarkan rentang tanggal terbit
    {
        if ($from && !$to) {
            return $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            return $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            return $query->whereBetween('created_at', [$from, $to]);
        }

        return $query;
    }
}
