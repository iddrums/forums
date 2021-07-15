<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Thread extends Model
{
    use HasFactory;

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];



    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread){
           $thread->replies->each->delete();
        });
    }


    public function path()

    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    public function creator()

    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function channel()

    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
       return $this->replies()->create($reply);

    //    $this->increment('replies_count');

    //    return $reply;
    }

    public function scopeFilter($query, $filters)

    {
      return $filters->apply($query);
    }
}
