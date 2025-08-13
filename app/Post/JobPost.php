<?php

namespace App\Post;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class JobPost - job repository
 */
class JobPost
{
    /**
     * @var Listing
     */
    protected $listing;

    /**
     * JobPost Constructor
     *
     * @param Listing $listing
     * @return void
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    /**
     * Upload an image and return its path.
     *
     * @param Request $data
     * @return string
     */
    public function getImagePath(Request $data)
    {
        return $data->file('feature_image')->store('images', 'public');
    }

    /**
     * Store a job.
     *
     * @param Request $data
     * @return void
     */
    public function store(Request $data): void
    {
        $this->listing->feature_image = $this->getImagePath($data);
        $this->listing->user_id = auth()->user()->id;
        $this->listing->title = $data['title'];
        $this->listing->description = $data['description'];
        $this->listing->roles = $data['roles'];
        $this->listing->job_type = $data['job_type'];
        $this->listing->address = $data['address'];
        $this->listing->application_close_date = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
        $this->listing->salary = $data['salary'];
        $this->listing->slug = Str::slug($data['title']) . '.' . Str::uuid();
        $this->listing->save();
    }

    /**
     * Update a job.
     *
     * @param int $id
     * @param Request $data
     * @return void
     */
    public function updatePost(int $id, Request $data): void
    {
        if ($data->hasFile('feature_image')) {
            $this->listing->find($id)->update(['feature_image' => $this->getImagePath($data)]);
        }

        $data['application_close_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        $this->listing->find($id)->update($data->except('feature_image'));
    }
}
