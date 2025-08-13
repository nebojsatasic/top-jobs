@extends('layouts.admin.main')
@section('title', 'Create Job')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">

            <h1>Post a job</h1>
            <form action="{{ route('job.store') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="form-group">
                    <label for="feature_image">Feature Image</label>
                    <input type="file" name="feature_image" id="feature_image" class="form-control">
                    @if ($errors->has('feature_image'))
                        <div class="error">{{ $errors->first('feature_image') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control">
                    @if ($errors->has('title'))
                        <div class="error">{{ $errors->first('title') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control summernote"></textarea>
                    @if ($errors->has('description'))
                        <div class="error">{{ $errors->first('description') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="roles">Roles and ResponsibilityDescription</label>
                    <textarea name="roles" id="roles" class="form-control summernote"></textarea>
                    @if ($errors->has('roles'))
                        <div class="error">{{ $errors->first('roles') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label>Job types</label>
                    <div class="form-check">
                    <input type="radio" class="form-check-input" name="job_type"  id="fulltime" value="Fulltime">
                    <label for="fulltime" class="form-check-label">Fulltime</label>
                    </div>
                    <div class="form-check">
                    <input type="radio" class="form-check-input" name="job_type"  id="parttime" value="Parttime">
                    <label for="parttime" class="form-check-label">Parttime</label>
                    </div>
                    <div class="form-check">
                    <input type="radio" class="form-check-input" name="job_type"  id="casual" value="Casual">
                    <label for="casual" class="form-check-label">Casual</label>
                    </div>
                    <div class="form-check">
                    <input type="radio" class="form-check-input" name="job_type"  id="contract" value="Contract">
                    <label for="contract" class="form-check-label">Contract</label>
                    </div>
                    @if ($errors->has('job_type'))
                        <div class="error">{{ $errors->first('job_type') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control">
                    @if ($errors->has('address'))
                        <div class="error">{{ $errors->first('address') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input type="text" name="salary" id="salary" class="form-control">
                    @if ($errors->has('salary'))
                        <div class="error">{{ $errors->first('salary') }}</div>
                    @endif
                </div>
                                <div class="form-group">
                    <label for="datepicker">Application closing date</label>
                    <input type="text" name="date" id="datepicker" class="form-control">
                    @if ($errors->has('date'))
                        <div class="error">{{ $errors->first('date') }}</div>
                    @endif
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">Post a job</button>
                </div>

            </form>
        </div>
    </div>
</div>
<style>
    .note-insert {
        display: none!important;
    }
    .error {
        color: red;
        font-weight: bold;
    }
</style>
@endsection