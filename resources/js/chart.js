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