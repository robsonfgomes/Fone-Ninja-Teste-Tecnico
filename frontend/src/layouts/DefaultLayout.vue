<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Fone Ninja ERP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link to="/" class="nav-link" exact-active-class="active">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/produtos" class="nav-link" active-class="active">
                                <i class="bi bi-bag me-1"></i> Produtos
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/compras" class="nav-link" active-class="active">
                                <i class="bi bi-cart me-1"></i> Compras
                            </router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/vendas" class="nav-link" active-class="active">
                                <i class="bi bi-basket me-1"></i> Vendas
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<style scoped>
.navbar {
    box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
}

.nav-link {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    margin: 0 0.25rem;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
}
</style>