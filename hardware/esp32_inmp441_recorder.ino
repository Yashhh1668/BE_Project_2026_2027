#include <Arduino.h>
#include <driver/i2s.h>

#define I2S_PORT I2S_NUM_0

#define I2S_SCK 18   // BCLK
#define I2S_WS  19   // LRCLK / WS
#define I2S_SD  23   // DATA

#define SAMPLE_RATE 16000


#define SERIAL_BAUD 921600

#define BUFFER_SAMPLES 256

int32_t rawBuffer[BUFFER_SAMPLES * 2];
int16_t pcmBuffer[BUFFER_SAMPLES];

bool recording = false;

void setupI2S()
{
  i2s_config_t config;

  config.mode =
      (i2s_mode_t)(I2S_MODE_MASTER | I2S_MODE_RX);

  config.sample_rate = SAMPLE_RATE;

 
  config.bits_per_sample = I2S_BITS_PER_SAMPLE_32BIT;

  
  config.channel_format = I2S_CHANNEL_FMT_RIGHT_LEFT;

 
  config.communication_format = I2S_COMM_FORMAT_STAND_I2S;

  config.intr_alloc_flags = ESP_INTR_FLAG_LEVEL1;

  config.dma_buf_count = 8;
  config.dma_buf_len = BUFFER_SAMPLES;

  config.use_apll = false;
  config.tx_desc_auto_clear = false;
  config.fixed_mclk = 0;

  i2s_driver_install(I2S_PORT, &config, 0, NULL);

  i2s_pin_config_t pins;

  pins.bck_io_num = I2S_SCK;
  pins.ws_io_num = I2S_WS;
  pins.data_out_num = I2S_PIN_NO_CHANGE;
  pins.data_in_num = I2S_SD;

  i2s_set_pin(I2S_PORT, &pins);

  i2s_zero_dma_buffer(I2S_PORT);

  i2s_start(I2S_PORT);
}


void setup()
{
  Serial.begin(SERIAL_BAUD);

  delay(1000);

  setupI2S();

  delay(500);

  Serial.println("ESP32_STT_READY");
}


void loop()
{
 

  if (!recording)
  {
    if (Serial.available())
    {
      String command = Serial.readStringUntil('\n');
      command.trim();

      if (command == "START")
      {
        recording = true;

     
        i2s_zero_dma_buffer(I2S_PORT);

        Serial.println("START_OK");
      }
    }

    return;
  }




  if (Serial.available())
  {
    String command = Serial.readStringUntil('\n');
    command.trim();

    if (command == "STOP")
    {
      recording = false;

      Serial.println("STOP_END");

      return;
    }
  }


  
  size_t bytesRead = 0;

  esp_err_t result = i2s_read(
      I2S_PORT,
      rawBuffer,
      sizeof(rawBuffer),
      &bytesRead,
      portMAX_DELAY
  );

  if (result != ESP_OK || bytesRead == 0)
  {
    return;
  }


 


  int total32BitValues = bytesRead / sizeof(int32_t);

  int outputSamples = 0;

  for (int i = 0; i + 1 < total32BitValues; i += 2)
  {
    int32_t sample = rawBuffer[i];

    sample >>= 8;
   
    sample >>= 8;

    if (sample > 32767)
      sample = 32767;

    if (sample < -32768)
      sample = -32768;

    pcmBuffer[outputSamples++] = (int16_t)sample;

    if (outputSamples >= BUFFER_SAMPLES)
      break;
  }



  if (outputSamples > 0)
  {
    Serial.write(
        (uint8_t *)pcmBuffer,
        outputSamples * sizeof(int16_t)
    );
  }
}
