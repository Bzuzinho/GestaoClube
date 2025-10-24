@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h5 class="mb-4">Mensalidades</h5>

    <form id="formMensalidades" method="POST" action="{{ route('mensalidades.store') }}">
        @csrf
        <table class="table table-sm table-bordered w-full text-sm align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Designação</th>
                    <th>Valor (€)</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody id="tabelaMensalidades">
                @foreach($mensalidades as $mensalidade)
                    <tr data-id="{{ $mensalidade->id }}">
                        <td><input type="text" class="form-control form-control-sm" value="{{ $mensalidade->designacao }}" readonly></td>
                        <td><input type="number" step="0.01" class="form-control form-control-sm" value="{{ $mensalidade->valor }}" readonly></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-editar">Editar</button>
                            <button type="button" class="btn btn-sm btn-outline-success btn-gravar d-none">Gravar</button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-apagar">Apagar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <button type="button" class="btn btn-secondary btn-sm" id="adicionarLinha">+ Adicionar Linha</button>
            <button type="submit" class="btn btn-primary btn-sm float-end">Gravar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#adicionarLinha').on('click', function () {
            const index = $('#tabelaMensalidades tr').length;
            $('#tabelaMensalidades').append(`
                <tr>
                    <td><input type="text" class="form-control form-control-sm" name="mensalidades[${index}][designacao]" required></td>
                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="mensalidades[${index}][valor]" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-apagar">Apagar</button>
                    </td>
                </tr>
            `);
        });

        $('#tabelaMensalidades').on('click', '.btn-apagar', function () {
            const row = $(this).closest('tr');
            const id = row.data('id');
            if (!id) return row.remove();

            if (!confirm('Tem a certeza que pretende eliminar esta mensalidade?')) return;

            $.ajax({
                url: `{{ url('gestao/mensalidades') }}/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => row.remove(),
                error: (xhr) => {
                    console.error(xhr);
                    alert('Erro ao apagar: ' + (xhr.responseJSON?.message ?? 'Erro desconhecido.'));
                }
            });
        });

        $('#tabelaMensalidades').on('click', '.btn-editar', function () {
            const row = $(this).closest('tr');
            row.find('input').prop('readonly', false);
            row.find('.btn-editar').addClass('d-none');
            row.find('.btn-gravar').removeClass('d-none');
        });

        $('#tabelaMensalidades').on('click', '.btn-gravar', function () {
            const row = $(this).closest('tr');
            const id = row.data('id');
            if (!id) return alert('ID em falta para esta linha.');

            const designacao = row.find('input').eq(0).val();
            const valor = row.find('input').eq(1).val();

            $.ajax({
                url: `{{ url('gestao/mensalidades') }}/${id}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    designacao,
                    valor
                },
                success: function () {
                    row.find('input').prop('readonly', true);
                    row.find('.btn-editar').removeClass('d-none');
                    row.find('.btn-gravar').addClass('d-none');
                },
                error: function (xhr) {
                    console.error(xhr);
                    alert('Erro ao gravar: ' + (xhr.responseJSON?.message ?? 'Erro desconhecido.'));
                }
            });
        });
    });
</script>
@endpush
