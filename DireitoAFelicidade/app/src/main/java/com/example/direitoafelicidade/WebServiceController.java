package com.example.direitoafelicidade;

import android.app.Application;
import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import modelDominio.Aplicativo;
import modelDominio.Artigo;
import modelDominio.CanalYoutube;
import modelDominio.Evento;
import modelDominio.Filme;
import modelDominio.PaginaWeb;
import modelDominio.Serie;
import modelDominio.Tematica;
import modelDominio.Usuario;
import modelDominio.Livro;

public class WebServiceController extends AppCompatActivity {
    String urlWebServicesDesenvolvimento = "http://192.168.0.111:80/projetoAndroid/";// O número deve ser o IPV4 de cada um
    ArrayList<CanalYoutube> listaYoutube;
    ArrayList<PaginaWeb> listaPaginasWeb;
    ArrayList<Serie> listaSeries;
    ArrayList<Livro> listaLivros;
    ArrayList<Filme> listaFilmes;
    ArrayList<Evento> listaEventos;
    ArrayList<Artigo> listaArtigos;

    int a =0;
    Boolean recebeu = false;
    Context context;


    public WebServiceController(Context context) {
        this.context = context;
    }

    public WebServiceController() {

    }




    public interface VolleyResponseListner {
        void onResponse(Object response);

        void onError(String message);
    }




    public void validarLogin(final Usuario usuario, final VolleyResponseListner volleyResponseListner) {
        Boolean resultado;



        Log.d("opa", "contrcto" + context);
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        Log.d("yes", "contrcto" + requestQueue);

        Log.d("ola", "cheguei");
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getUsuarios.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.v("LogLogin", response);
                        Log.d("Response + ", response);
                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            Log.d("Objeto: ", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");
                            Usuario usuarioLogado = null;
                            if (!isErro) {



                                usuarioLogado = JsonController.obtemUsuarioLogado(jsonObject);


                                volleyResponseListner.onResponse(usuarioLogado);


                            }

                        } catch (Exception e) {
                            Log.v("LogLogin", e.getMessage());
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("LogLogin", error.getMessage());
                        volleyResponseListner.onError(error.getMessage());
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("emailUsuario", usuario.getEmailUsuario());
                params.put("senhaUsuario", usuario.getSenhaUsuario());
                return params;
            }
        };




        requestQueue.add(stringRequest);




    }


    public void cadastrarUsuario(final Usuario usuario, final VolleyResponseListner volleyResponseListner) {

        RequestQueue requestQueue = Volley.newRequestQueue(context);


        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "insertUsuario.php", new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.v("Resposta", response);
                Log.d("Response + ", response);
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    Log.d("Objeto: ", jsonObject.toString());
                    boolean isErro = jsonObject.getBoolean("erro");

                    if (isErro) {
                        //Toast.makeText(getApplicationContext(), "Erro", Toast.LENGTH_LONG).show();
                    } else {

                        volleyResponseListner.onResponse(false);
                        /*Log.d("Nova Tela", response);
                        Intent novaTela = new Intent(WebServiceController.this, CategoriaActivity.class);
                        startActivity(novaTela);
                        finish();*/
                    }

                } catch (Exception e) {
                    Log.v("Cadastro", e.getMessage());
                }
            }
        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("Cadastro", error.getMessage());

                    }

                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("nomeUsuario", usuario.getNomeUsuario());
                params.put("generoUsuario", String.valueOf(usuario.getGeneroUsuario()));
                params.put("tipoUsuario", String.valueOf(usuario.getTipoUsuario()));
                params.put("emailUsuario", usuario.getEmailUsuario());
                params.put("senhaUsuario", usuario.getSenhaUsuario());
                return params;
            }
        };
        requestQueue.add(stringRequest);
    }


    public void carregaTematicas(final VolleyResponseListner volleyResponseListner)
    {

        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getTematicas.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca tematicas", response);

                        try
                        {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca tematicas", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if(isErro)
                            {
                                //Toast.makeText(getApplicationContext(),"Dados inválidos",Toast.LENGTH_SHORT).show();
                            }
                            else
                            {
                                JsonController jsonController = new JsonController();
                                ArrayList<Tematica> listaTematicas = jsonController.trataTematicas(jsonObject);
                                Log.d("Teste"," Lista de tematicas depois de chamar a fução controller " + listaTematicas);
                                // Nessa lista acima tem dados

                                if (listaTematicas != null)
                                {

                                    Log.d("Teste se entrou no IF", "Lista de tematicas dentro do IF: " + listaTematicas);

                                    volleyResponseListner.onResponse(listaTematicas);

                                }


                            }
                        }
                        catch(Exception e)
                        {
                            Log.d("Teste","Exceção no método carregaTematicas() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin",error.getMessage());
            }
        }){

        };
        requestQueue.add(stringRequest);

    }


    public void carregaAplicativos(final String codTematica, final VolleyResponseListner volleyResponseListner)
    {

        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getAplicativos.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Aplicativos", response);

                        try
                        {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Aplicativos", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if(isErro)
                            {
                                //Toast.makeText(getApplicationContext(),"Dados inválidos",Toast.LENGTH_SHORT).show();
                            }
                            else
                            {
                                JsonController jsonController = new JsonController();
                                ArrayList<Aplicativo> listaAplicativos = jsonController.obtemAplicativos(jsonObject,response);
                                Log.d("Teste"," Lista de Aplicativos depois de chamar a fução controller " + listaAplicativos);
                                // Nessa lista acima tem dados

                                if (listaAplicativos != null)
                                {

                                    Log.d("Teste se entrou no IF", "Lista de appps dentro do IF: " + listaAplicativos);

                                    volleyResponseListner.onResponse(listaAplicativos);

                                }
                            }
                        }
                        catch(Exception e)
                        {
                            Log.d("Teste","Exceção no método carregaApp() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin",error.getMessage());
            }
        }){

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }


    public void carregaCanais(final String codTematica, final VolleyResponseListner volleyResponseListner) {

        RequestQueue requestQueue = Volley.newRequestQueue(context);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getCanal.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Canais", response);

                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Canais", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if (isErro) {
                                Toast.makeText(context, "Dados inválidos", Toast.LENGTH_SHORT).show();
                            } else {
                                JsonController jsonController = new JsonController();
                                listaYoutube = jsonController.obtemCanaisYoutube(jsonObject, response);
                                Log.d("Teste", " Lista de Canais depois de chamar a fução controller " + listaYoutube);
                                // Nessa lista acima tem dados

                                if (listaYoutube != null) {
                                    Log.d("Teste se entrou no IF", "Lista de canais dentro do IF: " + listaYoutube);

                                    volleyResponseListner.onResponse(listaYoutube);

                                }
                            }
                        } catch (Exception e) {
                            Log.d("Teste", "Exceção no método carregaCanais() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin", error.getMessage());
            }
        })  {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };


        requestQueue.add(stringRequest);

    }

    public void carregaSeries(final String codTematica, final VolleyResponseListner volleyResponseListner) {
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getSeries.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca serie", response);

                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca serie", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if (isErro) {
                                //Toast.makeText(context, "Dados inválidos", Toast.LENGTH_SHORT).show();
                            } else {
                                JsonController jsonController = new JsonController();
                                listaSeries = jsonController.obtemSeries(jsonObject, response);
                                Log.d("Teste", " Lista de series depois de chamar a fução controller " + listaSeries);
                                // Nessa lista acima tem dados

                                if (listaSeries != null) {
                                    Log.d("Teste se entrou no IF", "Lista de series dentro do IF: " + listaSeries);

                                    volleyResponseListner.onResponse(listaSeries);

                                }
                            }
                        } catch (Exception e) {
                            Log.d("Teste", "Exceção no método carregaSites() " + e.getMessage());


                        }

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin", error.getMessage());
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }



    public void carregaSites(final String codTematica, final VolleyResponseListner volleyResponseListner) {
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getSites.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Sites", response);

                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Sites", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if (isErro) {
                                //Toast.makeText(context, "Dados inválidos", Toast.LENGTH_SHORT).show();
                            } else {
                                JsonController jsonController = new JsonController();
                                listaPaginasWeb = jsonController.obtemPaginasWeb(jsonObject, response);
                                Log.d("Teste", " Lista de Sites depois de chamar a fução controller " + listaPaginasWeb);
                                // Nessa lista acima tem dados

                                if (listaPaginasWeb != null) {
                                    Log.d("Teste se entrou no IF", "Lista de sites dentro do IF: " + listaPaginasWeb);

                                    volleyResponseListner.onResponse(listaPaginasWeb);

                                }
                            }
                        } catch (Exception e) {
                            Log.d("Teste", "Exceção no método carregaSites() " + e.getMessage());


                        }

                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin", error.getMessage());
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }

    public void carregaEventos(final String codTematica, final VolleyResponseListner volleyResponseListner) {
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                urlWebServicesDesenvolvimento + "getEventos.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Livros", response);

                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Livros", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if (isErro) {
                                Log.d("chegou", response.toString());
                                //Toast.makeText(context, "Dados inválidos", Toast.LENGTH_SHORT).show();
                            } else {
                                JsonController jsonController = new JsonController();
                                listaEventos = jsonController.obtemEventos(jsonObject, response);
                                Log.d("Teste", " Lista de eventos depois de chamar a fução controller " + listaEventos);
                                // Nessa lista acima tem dados

                                if (listaEventos != null) {
                                    Log.d("Teste se entrou no IF", "Lista de eventos dentro do IF: " + listaEventos);

                                    volleyResponseListner.onResponse(listaEventos);

                                }
                            }
                        } catch (Exception e) {
                            Log.d("Teste", "Exceção no método carregaEventos() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin", error.getMessage());
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }




    public void carregaLivros(final String codTematica, final VolleyResponseListner volleyResponseListner) {
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                urlWebServicesDesenvolvimento + "getLivros.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Livros", response);

                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Livros", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if (isErro) {
                                Log.d("chegou", response.toString());
                                //Toast.makeText(context, "Dados inválidos", Toast.LENGTH_SHORT).show();
                            } else {
                                JsonController jsonController = new JsonController();
                                listaLivros = jsonController.obtemLivros(jsonObject, response);
                                Log.d("Teste", " Lista de Livros depois de chamar a fução controller " + listaLivros);
                                // Nessa lista acima tem dados

                                if (listaLivros != null) {
                                    Log.d("Teste se entrou no IF", "Lista de livros dentro do IF: " + listaLivros);

                                    volleyResponseListner.onResponse(listaLivros);

                                    /*livroAdapter = new LivroAdapter(listaLivros, trataCliqueItem);
                                    rvLivros.setLayoutManager(new LinearLayoutManager(TelaLivros.this));
                                    rvLivros.setItemAnimator(new DefaultItemAnimator());
                                    rvLivros.setAdapter(livroAdapter);
                                    */

                                }
                            }
                        } catch (Exception e) {
                            Log.d("Teste", "Exceção no método carregaLivros() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin", error.getMessage());
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }


    public void carregaFilmes(final String codTematica, final  VolleyResponseListner volleyResponseListner) {
        RequestQueue requestQueue = Volley.newRequestQueue(context);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getFilmes.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Filmes", response);

                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Filmesss", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if (isErro) {
                                Toast.makeText(context, "Dados inválidos", Toast.LENGTH_SHORT).show();
                            } else {
                                JsonController jsonController = new JsonController();
                                listaFilmes = jsonController.obtemFilmes(jsonObject, response);
                                Log.d("Teste", " Lista de Filmes depois de chamar a fução controller " + listaFilmes);
                                // Nessa lista acima tem dados

                                if (listaFilmes != null) {
                                    Log.d("Teste se entrou no IF", "Lista de sites dentro do IF: " + listaFilmes);
                                    volleyResponseListner.onResponse(listaFilmes);
                                }
                            }
                        } catch (Exception e) {
                            Log.d("Teste", "Exceção no método carregaFilmes() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin", error.getMessage());
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }

    public void carregaArtigos(final String codTematica, final VolleyResponseListner volleyResponseListner) {

        listaArtigos = new ArrayList<>();
        RequestQueue requestQueue = Volley.newRequestQueue(context);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlWebServicesDesenvolvimento + "getArtigos.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Busca Artigos", response);

                        try
                        {
                            JSONObject jsonObject = new JSONObject(response);

                            Log.d("Busca Artigos", jsonObject.toString());

                            boolean isErro = jsonObject.getBoolean("erro");

                            if(isErro)
                            {

                            }
                            else
                            {
                                JsonController jsonController = new JsonController();
                                listaArtigos = jsonController.obtemArtigos(jsonObject,response);
                                Log.d("Teste"," Lista de Artigos depois de chamar a fução controller " + listaArtigos);
                                // Nessa lista acima tem dados

                                if (listaArtigos != null)
                                {
                                    Log.d("Teste se entrou no IF", "Lista de artigos dentro do IF: " + listaArtigos);

                                    volleyResponseListner.onResponse(listaArtigos);

                                    /*artigoAdapter = new ArtigoAdapter(listaArtigos, trataCliqueItem);
                                    rvArtigos.setLayoutManager(new LinearLayoutManager(TelaArtigos.this));
                                    rvArtigos.setItemAnimator(new DefaultItemAnimator());
                                    rvArtigos.setAdapter(artigoAdapter);*/
                                }
                            }
                        }
                        catch(Exception e)
                        {
                            Log.d("Teste","Exceção no método carregaArtigos() " + e.getMessage());


                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("LogLogin",error.getMessage());
            }
        }){

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("codTematica", codTematica);
                return params;
            }

        };
        requestQueue.add(stringRequest);

    }



}
