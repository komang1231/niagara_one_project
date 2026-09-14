<x-panel>
        <div>
            <x-filter.bar
                :clearable="['search', 'status']"
                ajax-target="#departemen-table"
            >
                <x-filter.search />

                <x-filter.multiselect
                    name="status"
                    label="Status"
                    :options="$statusOptions"
                />
            </x-filter.bar>
        </div>

        <hr class="app-panel__divider">

        <div id="departemen-table">
            <x-table>
                <thead>
                    <tr>
                        <th class="app-table__col-no">NO</th>
                        <th>Kode</th>
                        <th>Nama Departemen</th>
                        <th>Status</th>
                        <th class="app-table__col-actions">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($departemen as $i => $row)
                        <tr>
                            <td class="app-table__col-no">
                                {{ $i + 1 }}
                            </td>

                            <td>
                                {{ $row['kode'] }}
                            </td>

                            <td>
                                {{ $row['nama'] }}
                            </td>

                            <td>
                                <x-badge
                                    :variant="$row['status'] === 'aktif'
                                        ? 'success'
                                        : 'neutral'"
                                >
                                    {{ ucfirst($row['status']) }}
                                </x-badge>
                            </td>

                            <td class="app-table__col-actions">
                                <div class="app-table__actions">
                                  <x-button
                                    variant="icon-edit"
                                    icon="bi-pencil"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvas-departemen-edit"
                                    data-id="{{ $row['id'] }}"
                                   />

                                    <x-button
                                        variant="icon-danger"
                                        icon="bi-trash"
                                        href="#"
                                    />
                                      <x-table.status-toggle :checked="$row['status'] === 'aktif'" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-table.empty-row colspan="5" />
                    @endforelse
                </tbody>
            </x-table>

            <div class="app-table-footer">
                <span>
                    Menampilkan
                    {{ $departemen->count() }}
                    dari
                    {{ $departemen->count() }}
                    entri
                </span>
            </div>
        </div>
    </x-panel>