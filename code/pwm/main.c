#include <avr/io.h>
#include <util/delay.h>

int main(void)
{
	// Set PD5 (OC1A) as output pin
	DDRD |= (1 << PD5);

	// Set Timer/Counter1 in Fast PWM mode, with non-inverted PWM output on OC1A
	TCCR1A |= (1 << COM1A1) | (1 << WGM11) | (1 << WGM10);
	TCCR1B |= (1 << WGM12) | (1 << CS11);

	// Set the prescaler value to divide the clock by 2 (producing a 1 MHz timer clock)
	TCCR1B |= (1 << CS10);

	// Calculate the value of OCR1A for a 70% duty cycle (70% of 0xFFFF)
	uint16_t ocr1a_value = 0xB3FF;

	// Set the output compare register (OCR1A) to produce a 70% duty cycle
	OCR1A = 45874;

	// Enable interrupts (if needed)
	//sei();

	while (1)
	{
		// Delay for 10ms
		_delay_ms(10);
	}
	return 0;
}

