package com.example.direitoafelicidade;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.view.View;

import java.util.ArrayList;

import modelDominio.Serie;
import modelDominio.Tematica;

public class SerieDetalhadaActivity extends AppCompatActivity {
    EditText etSerieDetalhadaNomeSerie, etSerieDetalhadaDescricaoSerie, etSerieDetalhadaDescricaoIndicacaoSerie, etSerieDetalhadaSinopseSerie, etSerieDetalhadaQuantidadeTemporadasSerie, etSerieDetalhadaAnoLancamentoSerie, etSerieDetalhadaTematicasSerie, etSerieDetalhadaPlataformaSerie;
    ImageView ivSerieDetalhadaCapaSerie;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_serie_detalhada);
        Toolbar toolbar = findViewById(R.id.toolbar);
        etSerieDetalhadaNomeSerie = findViewById(R.id.etSerieDetalhadaNomeSerie);
        etSerieDetalhadaDescricaoSerie = findViewById(R.id.etSerieDetalhadaDescricaoSerie);
        etSerieDetalhadaDescricaoIndicacaoSerie = findViewById(R.id.etSerieDetalhadaDescricaoIndicacaoSerie);
        etSerieDetalhadaSinopseSerie = findViewById(R.id.etSerieDetalhadaSinopseSerie);
        etSerieDetalhadaQuantidadeTemporadasSerie = findViewById(R.id.etSerieDetalhadaQuantidadeTemporadasSerie);
        etSerieDetalhadaAnoLancamentoSerie = findViewById(R.id.etSerieDetalhadaAnoLancamentoSerie);
        etSerieDetalhadaTematicasSerie = findViewById(R.id.etSerieDetalhadaTematicasSerie);
        etSerieDetalhadaPlataformaSerie = findViewById(R.id.etSerieDetalhadaPlataformaSerie);
        ivSerieDetalhadaCapaSerie = findViewById(R.id.ivSerieDetalahaCapaSerie);

        setSupportActionBar(toolbar);


        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        Intent it = getIntent();
/*
        if(it != null && it.hasExtra("serie")){

            Serie serie = (Serie) it.getSerializableExtra("serie");

            etSerieDetalhadaNomeSerie.setText(serie.getNomeConteudo());
            etSerieDetalhadaDescricaoSerie.setText(serie.getDescricaoConteudo());
            etSerieDetalhadaDescricaoIndicacaoSerie.setText(serie.getDescricaoIndicacao());
            etSerieDetalhadaSinopseSerie.setText(serie.getSinopseSerie());
            etSerieDetalhadaQuantidadeTemporadasSerie.setText(serie.getTemporadaSerie());
            etSerieDetalhadaAnoLancamentoSerie.setText(serie.getAnoLancamentoSerie());

            //tratamento das tematicas
            String tematicasStringSerie = "";
            for(int i = 0; i < serie.getTematicas().size(); i++){
                Tematica tematica = serie.getTematicas().get(i);
                tematicasStringSerie = tematica.getNomeTematica() + "\n";
            }

            etSerieDetalhadaTematicasSerie.setText(tematicasStringSerie);

            etSerieDetalhadaPlataformaSerie.setText(serie.getPlataformaSerie());

            byte[] capaSerieByte = serie.getCapaSerieByte();
            Bitmap capaSerieBitmap = BitmapFactory.decodeByteArray(capaSerieByte, 0, capaSerieByte.length);

            ivSerieDetalhadaCapaSerie.setImageBitmap(capaSerieBitmap);





        }

 */


    }

}
