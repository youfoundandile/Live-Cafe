{{-- resources/views/admin/announcements/fields.blade.php  --}}
<label>Title
    <input type="text" name="title" value="{{ old('title', $announcement->title) }}" maxlength="150" required>
</label>

<label>Message
    <textarea name="description" rows="4" maxlength="5000" required>{{ old('description', $announcement->description) }}</textarea>
</label>

<label>About a run or event (optional)
    <select name="event_id">
        <option value="">Not linked</option>
        @foreach ($events as $event)
            <option value="{{ $event->id }}" @selected((string) old('event_id', $announcement->event_id) === (string) $event->id)>
                {{ $event->event_date->format('D d M') }}: {{ $event->title }}
            </option>
        @endforeach
    </select>
</label>

<label class="checkbox">
    <input type="hidden" name="is_pinned" value="0">
    <input type="checkbox" name="is_pinned" value="1" @checked(old('is_pinned', $announcement->is_pinned))>
    Pin to the top
</label>

{{-- Radios, not two submit buttons: the double-click guard switches buttons off, and a switched-off button's value isn't sent --}}
@php($action = old('action', $announcement->exists && ! $announcement->isPublished() ? 'draft' : 'publish'))
<fieldset>
    <legend>Who sees it?</legend>
    <label class="checkbox"><input type="radio" name="action" value="publish" @checked($action === 'publish')> Publish now: members see it on the run club pages</label>
    <label class="checkbox"><input type="radio" name="action" value="draft" @checked($action === 'draft')> Save as a draft: only admins see it</label>
</fieldset>