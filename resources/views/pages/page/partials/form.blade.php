<div class="form-group">
    <label for="title">@lang('title.title')</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="@lang('title.title')" value="{{ old('title', $page->title) }}">
</div>
<div class="form-group">
    <label for="content">@lang('title.content')</label>
    <textarea class="form-control" id="content" name="content" rows="10" placeholder="@lang('title.content')" aria-describedby="contentHelp">{{ old('content', $page->content) }}</textarea>
    <small id="contentHelp" class="form-text text-muted">
        <a href="@lang('phrase.markdown-formatting-help-link-url')" target="_blank">@lang('phrase.markdown-formatting-help-link')</a>
        @lang('phrase.content-help')
    </small>
</div>
<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published" value="1" {{ old('published', $page->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>