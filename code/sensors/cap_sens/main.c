#include <avr/io.h>
#include <stdio.h>
#include <stdlib.h>
#define F_CPU 8000000L
#include <util/delay.h>

#define BAUDRATE 8

#define cap_sen_t1_low PE0
#define cap_sen_t1_high PE1
#define cap_sen_t2_low PC3
#define cap_sen_t2_high PC2
#define cap_sen_pt_high PC1

void usart_init(void);
void usart_tx(unsigned char);

void cap_sensors_init(void);

int main (void)
{
	usart_init();
	cap_sensors_init();

	int cap_sen_t1_low_val = 0;
	int cap_sen_t1_high_val = 0;
	int cap_sen_t2_low_val = 0;
	int cap_sen_t2_high_val = 0;
	int cap_sen_pt_high_val = 0;

	while(1){
		cap_sen_t1_low_val = (PINE & (1 << cap_sen_t1_low)) >> cap_sen_t1_low;
		_delay_ms(100);
		usart_tx(cap_sen_t1_low_val);
		
		/*_delay_ms(1000);
		
		cap_sen_t1_high_val = (PINE & (1 << cap_sen_t1_high)) >> cap_sen_t1_high;
		_delay_ms(100);
		usart_tx(cap_sen_t1_high_val);
		
		_delay_ms(1000);
		
		cap_sen_t2_low_val = (PINC & (1 << cap_sen_t2_low)) >> cap_sen_t2_low;
		_delay_ms(100);
		usart_tx(cap_sen_t2_low_val);
		
		_delay_ms(1000);
		
		cap_sen_t2_high_val = (PINC & (1 << cap_sen_t2_high)) >> cap_sen_t2_high;
		_delay_ms(100);
		usart_tx(cap_sen_t2_high_val);
		
		_delay_ms(1000);
		
		cap_sen_pt_high_val = (PINC & (1 << cap_sen_pt_high)) >> cap_sen_pt_high;
		_delay_ms(100);
		usart_tx(cap_sen_pt_high_val);
		
		_delay_ms(1000);*/
		
	}
	return 0;
}

void usart_init (void){
	UBRRL=BAUDRATE;
	UCSRB|=(1<<TXEN)|(1<<RXEN);
	UCSRC|=(1<<URSEL)|(1<<UCSZ0)|(1<<UCSZ1);
}

void usart_tx (unsigned char data){
	while (!( UCSRA & (1<<UDRE)));
	UDR = data;
}


void cap_sensors_init(void){
	DDRE &= ~(1 << cap_sen_t1_low) & ~(1 << cap_sen_t1_high);
	DDRC &= ~(1 << cap_sen_t2_low) & ~(1 << cap_sen_t2_high) & ~(1 << cap_sen_pt_high);
	
	return 0;
}
