﻿using System;
using System.Collections.Generic;

namespace LabWork1.HistoryManaging
{
    public class HistoryManager : IHistoryManager
    {
        private readonly List<string> _history;

        public HistoryManager()
        {
            _history = new List<string>();
        }

        public void Clear()
        {
            _history.Clear();
        }

        public void Print()
        {
            foreach (var str in _history)
            {
                Console.WriteLine(str);
            }
        }

        public void AddLine(string log)
        {
            _history.Add(log);
        }
    }
}