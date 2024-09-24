@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Gestion des Produits</h1>

        <a href="{{ route('products.create') }}" class="btn btn-primary mb-4">Créer un nouveau produit</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="py-2 px-4">Nom</th>
                    <th class="py-2 px-4">Description</th>
                    <th class="py-2 px-4">Prix</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="border py-2 px-4">{{ $product->name }}</td>
                        <td class="border py-2 px-4">{{ $product->description }}</td>
                        <td class="border py-2 px-4">{{ $product->price }}</td>
                        <td class="border py-2 px-4">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
