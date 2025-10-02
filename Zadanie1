using System;

class OperacjeNaTablicy
{
    private int[] tab;
    private int liczbaElementow;

    public OperacjeNaTablicy(int rozmiar)
    {
        liczbaElementow = rozmiar;
        tab = new int[liczbaElementow];
        Random los = new Random();
        for (int i = 0; i < liczbaElementow; i++)
        {
            tab[i] = los.Next(1, 1001);
        }
    }

    public void Wyswietl()
    {
        for (int i = 0; i < liczbaElementow; i++)
        {
            Console.WriteLine($"{i}: {tab[i]}");
        }
    }

    public int Szukaj(int wartosc)
    {
        for (int i = 0; i < liczbaElementow; i++)
        {
            if (tab[i] == wartosc)
            {
                return i;
            }
        }
        return -1;
    }

    public int WyswietlNieparzyste()
    {
        Console.WriteLine("\nLiczby nieparzyste:");
        int ile = 0;
        for (int i = 0; i < liczbaElementow; i++)
        {
            if (tab[i] % 2 != 0)
            {
                Console.Write($"{tab[i]} ");
                ile++;
            }
        }
        return ile;
    }

    public double Srednia()
    {
        int suma = 0;
        for (int i = 0; i < liczbaElementow; i++)
        {
            suma += tab[i];
        }
        return (double)suma / liczbaElementow;
    }
}

public class Program
{
    public static void Main(string[] args)
    {
        OperacjeNaTablicy t = new OperacjeNaTablicy(25);

        Console.WriteLine("Zawartość tablicy:");
        t.Wyswietl();

        Console.Write("\nPodaj liczbę do wyszukania: ");
        int szukana = int.Parse(Console.ReadLine());
        int indeks = t.Szukaj(szukana);

        if (indeks != -1)
        {
            Console.WriteLine($"Liczba {szukana} znaleziona na indeksie {indeks}.");
        }

        int ileNieparzystych = t.WyswietlNieparzyste();
        Console.WriteLine($"\n\nRazem nieparzystych: {ileNieparzystych}");

        double sr = t.Srednia();
        Console.WriteLine($"Średnia wszystkich elementów: {sr}");
    }
}
