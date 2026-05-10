<x-app-layout title="Page Settings | Fitx Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1"><h4 class="fs-18 fw-semibold m-0">Page Settings</h4></div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Page Settings</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card bg-light-subtle border shadow-none">
                        <div class="card-header bg-transparent border-bottom">
                            <h5 class="card-title mb-0">Page Titles & FAQ Content</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update-page-setting') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Home Page Title</label>
                                    <input type="text" class="form-control" name="home_page_title" value="{{ $settings->get('home_page_title') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">About Page Title</label>
                                    <input type="text" class="form-control" name="about_page_title" value="{{ $settings->get('about_page_title') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Contact Page Title</label>
                                    <input type="text" class="form-control" name="contact_page_title" value="{{ $settings->get('contact_page_title') }}">
                                </div>
                                <hr class="my-4">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">FAQ Page Title</label>
                                    <input type="text" class="form-control" name="faq_page_title" value="{{ $settings->get('faq_page_title', 'FAQ / Legal Guides') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">FAQ Intro</label>
                                    <textarea class="form-control" name="faq_page_intro" rows="3">{{ $settings->get('faq_page_intro', 'Answers to common questions and the rules that apply when using NCS Hindi music.') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">FAQ Content</label>
                                    <textarea class="form-control" name="faq_page_content" rows="8" placeholder="<h5>Question</h5><p>Answer</p>">{{ $settings->get('faq_page_content') }}</textarea>
                                    <small class="text-muted">You can use simple HTML like headings, paragraphs, lists, and links.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Legal Guides Title</label>
                                    <input type="text" class="form-control" name="legal_page_title" value="{{ $settings->get('legal_page_title', 'Legal Guides') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Legal Guides Intro</label>
                                    <textarea class="form-control" name="legal_page_intro" rows="3">{{ $settings->get('legal_page_intro', 'Simple usage guidance for creators, brands, and community members.') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Legal Guides Content</label>
                                    <textarea class="form-control" name="legal_page_content" rows="8" placeholder="<h5>Usage Rule</h5><p>Rule text</p>">{{ $settings->get('legal_page_content') }}</textarea>
                                    <small class="text-muted">This content appears on the public FAQ page. Keep it creator-friendly and easy to read.</small>
                                </div>
                                <hr class="my-4">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">Global Song License Text</label>
                                    <textarea class="form-control" name="global_license_text" rows="6">{{ $settings->get('global_license_text', 'This music is safe for use in YouTube, Twitch, Shorts, Reels, and other social media content. You may use this track in monetized videos as long as you give clear credit in the description. Include the track title and artist name, and do not claim the music as your own original composition.') }}</textarea>
                                    <small class="text-muted">This text will be shown on all song detail pages unless a specific license is provided (if still supported).</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Page Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
