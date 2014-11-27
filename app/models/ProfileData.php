<?php 

class ProfileData extends Eloquent {

    use \Laracasts\Presenter\PresentableTrait;

    protected $fillable = [
        'twitter', 'facebook', 'google_plus', 'github', 'irc', 'website', 'tagline', 'description', 'skills', 'profile_photo', 'profile_photo_private', 'new_profile_photo', 'profile_photo_on_wall'
    ];

    protected $table = 'profile_data';

    protected $presenter = 'BB\Presenters\ProfileDataPresenter';

    protected $auditFields = array('profile_photo', 'profile_photo_on_wall');


    public static function boot()
    {
        parent::boot();

        self::observe(new \BB\Observer\UserAuditObserver());
    }

    public function setSkillsAttribute($skills)
    {
        if (is_array($skills)) {
            $this->attributes['skills_array'] = json_encode($skills);
        } else {
            $this->attributes['skills_array'] = json_encode([$skills]);
        }
    }


    public function getSkillsAttribute()
    {
        return (array)json_decode($this->skills_array);
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * @return array
     */
    public function getAuditFields()
    {
        return $this->auditFields;
    }
} 