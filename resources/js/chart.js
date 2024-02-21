import Chart from 'chart.js/auto';
import * as helpers from './helpers';

( function() {

    document.addEventListener("DOMContentLoaded", () => {
        if(document.querySelector("#contenedor-stats")) {

            const tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

            consulta();
            async function consulta () {
                const ventasTodas = await ventas();
                console.log(ventasTodas);
    
                const comprasTodas = await compras();
                console.log(comprasTodas);
                
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

            ///
            // const config = {
            //     type: 'pie',
            //     data: data,
            //     options: {
            //       responsive: true,
            //       plugins: {
            //         legend: {
            //           position: 'top',
            //         },
            //         title: {
            //           display: true,
            //           text: 'Chart.js Pie Chart'
            //         }
            //       }
            //     },
            //   };
            ///

            function graficoCategorias(resultadoCategoria) {
                new Chart(
                  document.getElementById('stats-categorias'),
                  {
                    type: 'pie',
                    data: {
                      labels: resultadoCategoria.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Categorias',
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
                    type: 'pie',
                    data: {
                      labels: resultadoProvider.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Providers',
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
                    type: 'pie',
                    data: {
                      labels: resultadoFabricante.map(producto => producto.nombre),
                      datasets: [
                        {
                          label: 'Fabricantes',
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

























        }
    });
})();