import Chart from 'chart.js/auto';
import * as helpers from './helpers';

( function() {

    document.addEventListener("DOMContentLoaded", () => {
        if(document.querySelector("#contenedor-stats")) {

            const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const gananciaMesActual = document.querySelector("#stats-ganancia-mes-actual");

            cargarGraficos();

            async function cargarGraficos(){

                const masBuscados = await buscados();
                const masStock = await stock();

                graficoBuscados(masBuscados.buscados);
                // document.querySelector('#stats-total-invertido').textContent = helpers.formatearDinero(masBuscados.total_invertido);
            
                graficoStock(masStock.stock);
                graficoCategorias(masBuscados.categorias_datos);
                graficoProviders(masBuscados.providers_datos);
                graficoFabricantes(masBuscados.fabricantes_datos);



            }

            async function buscados() {

                try {
                    const url = '/api/stats/buscados';
    
                    const respuesta = await fetch(url, {
                        headers: {
                            'X-CSRF-TOKEN': tokenCSRF
                        },
                    });
    
                    let resultado = await respuesta.json();
    
                    return resultado;

    
                } catch (error) {
                    console.log(error);
                }
            }

            async function stock() {

                try {
                    const url = '/api/stats/stock';
    
                    const respuesta = await fetch(url, {
                        headers: {
                            'X-CSRF-TOKEN': tokenCSRF
                        },
                    });
    
                    let resultado = await respuesta.json();
    
                    return resultado;

    
                } catch (error) {
                    console.log(error);
                }
            }




            function graficoCategorias(resultadoCategoria) {
                new Chart(
                  document.getElementById('stats-categorias'),
                  {
                    options: {
                        plugins: {
                            legend: false
                        }
                    },
                    type: 'pie',
                    data: {
                      labels: resultadoCategoria.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Productos',
                          data: resultadoCategoria.map(producto => producto.cantidad)
                        }
                      ]
                    }
                  }
                );
            }

            function graficoProviders(resultadoProvider) {
                new Chart(
                  document.getElementById('stats-providers'),
                  {
                    options: {
                        plugins: {
                            legend: false
                        }
                    },
                    type: 'pie',
                    data: {
                      labels: resultadoProvider.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Productos',
                          data: resultadoProvider.map(producto => producto.cantidad)
                        }
                      ]
                    }
                  }
                );
            }

            function graficoFabricantes(resultadoFabricante) {
                new Chart(
                  document.getElementById('stats-fabricantes'),
                  {
                    options: {
                        plugins: {
                            legend: false
                        }
                    },
                    type: 'pie',
                    data: {
                      labels: resultadoFabricante.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Productos',
                          data: resultadoFabricante.map(producto => producto.cantidad)
                        }
                      ]
                    }
                  }
                );
            }


            function graficoStock(masStock) {
                new Chart(
                  document.getElementById('stats-stock'),
                  {
                    type: 'bar',
                    data: {
                      labels: masStock.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Más Stock',
                          data: masStock.map(producto => producto.stock)
                        }
                      ]
                    }
                  }
                );
            }
            

            function graficoBuscados(masBuscados) {
                new Chart(
                  document.getElementById('stats-buscados'),
                  {
                    type: 'bar',
                    data: {
                      labels: masBuscados.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Más buscados',
                          data: masBuscados.map(producto => producto.contador_show)
                        }
                      ]
                    }
                  }
                );

            }
            

            function graficoVentas(ventas) {

                new Chart(
                  document.getElementById('stats-ventas'),
                  {

                    type: 'line',
                    data: {
                        labels: ventas.map(venta => venta.mes),
                        datasets: [{
                          label: 'Ventas Anuales',
                          data: ventas.map(venta => venta.ganancia),
                          fill: false,
                          borderColor: 'rgb(75, 192, 192)',
                          tension: 0.1
                        }]

                    }
                  }
                );
            }

            function graficoCompras(compras) {

                console.log(compras);

                new Chart(
                  document.getElementById('stats-compras'),
                  {

                    type: 'line',
                    data: {
                        labels: compras.map(compra => compra.mes),
                        datasets: [{
                          label: 'Compras Anuales',
                          data: compras.map(compra => compra.gasto),
                          fill: false,
                          borderColor: 'rgb(75, 192, 192)',
                          tension: 0.1
                        }]

                    }
                  }
                );
            }




            consulta();
            async function consulta () {
                const ventasTodas = await ventas();
                graficoVentas(ventasTodas);
                gananciaMesActual.textContent = "$ " + helpers.formatearDinero(ventasTodas[ventasTodas.length - 1].ganancia);

    
                const comprasTodas = await compras();
                graficoCompras(comprasTodas);

                
            }

            async function ventas() {

                // Consultar Categorias, Fabricantes y Providers
                try {
                    const url = '/api/ventas/all';
    
                    const respuesta = await fetch(url, {
                        headers: {
                            'X-CSRF-TOKEN': tokenCSRF
                        },
                    });
    
                    const resultado = await respuesta.json();
                    return resultado;
    
                } catch (error) {
                    console.log(error);
                }
            }

            async function compras() {

                // Consultar Categorias, Fabricantes y Providers
                try {
                    const url = '/api/compras/all';
    
                    const respuesta = await fetch(url, {
                        headers: {
                            'X-CSRF-TOKEN': tokenCSRF
                        },
                    });
    
                    const resultado = await respuesta.json();
                    return resultado;
    
                } catch (error) {
                    console.log(error);
                }
            }

























        }
    });
})();