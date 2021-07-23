<?php

namespace App\Models;

use App\Visits;
use App\RecordsVisits;
use App\RecordsActivity;
use Illuminate\Support\Str;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Thread extends Model
{
    use HasFactory;

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread){
           $thread->replies->each->delete();
        });
    }


    public function path()

    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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

       $reply = $this->replies()->create($reply);

       event(new ThreadReceivedNewReply($reply));

       return $reply;

    }

    public function scopeFilter($query, $filters)

    {
      return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
       $this->subscriptions()->create([

        'user_id' => $userId ?: auth()->id()
    ]);
       return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function subscriptions()
    {
     return  $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
     return  $this->subscriptions()
                  ->where('user_id', auth()->id())
                  ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

         return  $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
         return 'slug';
    }

    public function setSlugAttribute($title)
    {
        $this->attributes['slug'] = $this->incrementSlug($title);
    }

    private function incrementSlug($title)
    {
        $slug = Str::slug($title, '-');
        $count = Thread::where('slug', 'LIKE', "{$slug}%")->count();
        $newCount = $count > 0 ? ++$count : '';
        return $newCount > 0 ? "$slug-$newCount" : $slug;
    }

}
