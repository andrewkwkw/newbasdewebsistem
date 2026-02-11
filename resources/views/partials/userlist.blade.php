<div class="form-group ml-4 mr-4">
    <label>Pilih User <span class="text-danger">*</span></label>
    @error('user_ids')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user as $u)
                    <tr>
                        <td>
                            <input type="checkbox" name="user_ids[]" value="{{ $u->id }}"
                                {{ in_array($u->id, old('user_ids', [])) ? 'checked' : '' }}>
                        </td>
                        <td>{{ $u->username }}</td>
                        <td>{{ $u->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
